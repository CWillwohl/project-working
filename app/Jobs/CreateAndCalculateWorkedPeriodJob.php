<?php

namespace App\Jobs;

use App\Models\{PunchClock, WorkingPeriod};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class CreateAndCalculateWorkedPeriodJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected PunchClock $punchClock
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if($this->punchClock->punch_type == 0) {
            WorkingPeriod::query()->create([
                'user_id'          => $this->punchClock->user_id,
                'project_id'       => $this->punchClock->project_id,
                'punch_in_id'      => $this->punchClock->id,
                'punch_out_id'     => null,
                'punch_in_time'    => $this->punchClock->punch_time,
                'punch_out_time'   => null,
                'value_to_receive' => 0,
                'time_worked'      => '00:00',
                'received'         => false,
            ]);

            return;
        }

        $workingPeriod = WorkingPeriod::query()
            ->whereProjectId($this->punchClock->project_id)
            ->whereUserId($this->punchClock->user_id)
            ->latest('id')
            ->first();

        $workingPeriod->update([
            'punch_out_id'     => $this->punchClock->id,
            'punch_out_time'   => $this->punchClock->punch_time,
            'value_to_receive' => $workingPeriod->calculateValueToReceive($this->punchClock->punch_time, $this->punchClock->punch_type),
            'time_worked'      => $workingPeriod->calculateTimeWorked($this->punchClock->punch_time, $this->punchClock->punch_type),
        ]);
    }
}
