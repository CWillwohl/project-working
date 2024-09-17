<?php

namespace App\Observers;

use App\Jobs\UpdatePriceToReceiveInProjectJob;
use App\Models\WorkingPeriod;

class WorkingPeriodObserver
{
    public function updated(WorkingPeriod $workingPeriod): void
    {
        if ($workingPeriod->isDirty('description') && array_key_exists('description', $workingPeriod->getDirty())) {
            return;
        }

        UpdatePriceToReceiveInProjectJob::dispatch($workingPeriod);
    }
}
