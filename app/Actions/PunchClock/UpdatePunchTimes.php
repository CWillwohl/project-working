<?php

namespace App\Actions\PunchClock;

use App\Models\WorkingPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdatePunchTimes
{
    public function execute(int $periodId, string $start, ?string $end): void
    {
        DB::transaction(function () use ($periodId, $start, $end) {
            $period = WorkingPeriod::where('user_id', auth()->id())->lockForUpdate()->findOrFail($periodId);
            $project = $period->project()->lockForUpdate()->firstOrFail();
            $startTime = Carbon::parse($start);
            $endTime = $period->punch_out_id && $end ? Carbon::parse($end) : null;

            if ($period->punch_out_id && (!$endTime || $endTime->lessThanOrEqualTo($startTime))) {
                throw ValidationException::withMessages(['end' => 'A saída deve ser posterior à entrada.']);
            }

            $period->punchIn()->where('user_id', auth()->id())->firstOrFail()->update(['punch_time' => $startTime]);
            if ($period->punch_out_id) {
                $period->punchOut()->where('user_id', auth()->id())->firstOrFail()->update(['punch_time' => $endTime]);
            }

            $seconds = $endTime ? $startTime->diffInSeconds($endTime) : 0;
            $value = round($seconds / 3600 * (float) $project->price_per_hour, 2);
            $oldValue = (float) $period->value_to_receive;
            $wasReceived = $period->received;
            $period->update([
                'punch_in_time' => $startTime,
                'punch_out_time' => $endTime,
                'time_worked' => sprintf('%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60)),
                'value_to_receive' => $value,
                'received' => false,
            ]);
            if ($wasReceived) {
                $project->decrement('total_received', $oldValue);
                $project->increment('price_to_receive', $value);
            } else {
                $project->increment('price_to_receive', $value - $oldValue);
            }
        }, 3);
    }
}
