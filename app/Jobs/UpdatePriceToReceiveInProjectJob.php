<?php

namespace App\Jobs;

use App\Models\WorkingPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class UpdatePriceToReceiveInProjectJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected WorkingPeriod $workingPeriod
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $project        = $this->workingPeriod->project;
        $valueToReceive = floatval($this->workingPeriod->project->price_to_receive) + floatval($this->workingPeriod->value_to_receive);

        $project->update(['price_to_receive' => $valueToReceive]);
    }
}
