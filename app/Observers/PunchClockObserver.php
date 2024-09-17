<?php

namespace App\Observers;

use App\Jobs\{CreateAndCalculateWorkedPeriodJob, UpdateAndCalculateWorkedPeriodJob};
use App\Models\{PunchClock, WorkingPeriod};

class PunchClockObserver
{
    /**
     * Handle the PunchClock "created" event.
     */
    public function created(PunchClock $punchClock): void
    {
        CreateAndCalculateWorkedPeriodJob::dispatch($punchClock);
    }
}
