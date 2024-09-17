<?php

namespace App\Actions\PunchClock;

use App\Models\{Project, PunchClock};
use Carbon\Carbon;

class RegisterNewPunchClockAction
{
    public function __construct(
        protected Project $project,
    ) {
    }

    public function execute(): void
    {
        $user = auth()->user();

        $actualDateTime = Carbon::now()->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:00');

        $lastRegister = PunchClock::query()
            ->whereUserId($user->id)
            ->whereProjectId($this->project->id)
            ->latest()
            ->first();

        if (!$lastRegister || $lastRegister->punch_type == 1) {
            PunchClock::query()->create([
                'user_id'    => $user->id,
                'project_id' => $this->project->id,
                'punch_time' => $actualDateTime,
                'punch_type' => 0,
            ]);

            return;
        }

        PunchClock::query()->create([
            'user_id'    => $user->id,
            'project_id' => $this->project->id,
            'punch_time' => $actualDateTime,
            'punch_type' => 1,
        ]);
    }
}
