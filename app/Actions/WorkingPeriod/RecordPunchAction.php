<?php

namespace App\Actions\WorkingPeriod;

use App\Models\{PunchClock, WorkingPeriod};

class RecordPunchAction
{
    public function execute(PunchClock $punchClock): void
    {
        if ($punchClock->punch_type == 0) {
            WorkingPeriod::query()->create([
                'user_id'          => $punchClock->user_id,
                'project_id'       => $punchClock->project_id,
                'punch_in_id'      => $punchClock->id,
                'punch_out_id'     => null,
                'punch_in_time'    => $punchClock->punch_time,
                'punch_out_time'   => null,
                'value_to_receive' => 0,
                'time_worked'      => '00:00',
                'received'         => false,
            ]);

            return;
        }

        $workingPeriod = WorkingPeriod::query()
            ->whereProjectId($punchClock->project_id)
            ->whereUserId($punchClock->user_id)
            ->whereNull('punch_out_id')
            ->latest('id')
            ->firstOrFail();

        $valueToReceive = $workingPeriod->calculateValueToReceive($punchClock->punch_time, $punchClock->punch_type);

        $workingPeriod->update([
            'punch_out_id'     => $punchClock->id,
            'punch_out_time'   => $punchClock->punch_time,
            'value_to_receive' => $valueToReceive,
            'time_worked'      => $workingPeriod->calculateTimeWorked($punchClock->punch_time, $punchClock->punch_type),
        ]);

        $workingPeriod->project()->increment('price_to_receive', (float) $valueToReceive);
    }
}
