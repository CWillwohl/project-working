<?php

namespace App\Actions\PunchClock;

use App\Actions\WorkingPeriod\RecordPunchAction;
use App\Models\{Project, PunchClock};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegisterNewPunchClockAction
{
    public function __construct(
        protected Project $project,
    ) {
    }

    public function execute(): void
    {
        $user           = auth()->user();
        $actualDateTime = Carbon::now()->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:00');

        DB::transaction(function () use ($user, $actualDateTime) {
            $project = $user->projects()->lockForUpdate()->findOrFail($this->project->id);

            $lastRegister = PunchClock::query()
                ->whereUserId($user->id)
                ->whereProjectId($project->id)
                ->latest('id')
                ->first();

            $punchClock = PunchClock::query()->create([
                'user_id'    => $user->id,
                'project_id' => $project->id,
                'punch_time' => $actualDateTime,
                'punch_type' => $lastRegister?->punch_type === 0 ? 1 : 0,
            ]);

            (new RecordPunchAction())->execute($punchClock);
        }, 3);
    }
}
