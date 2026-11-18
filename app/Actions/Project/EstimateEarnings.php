<?php

namespace App\Actions\Project;

use App\Models\Project;
use Carbon\CarbonImmutable;

class EstimateEarnings
{
    public const WEEKDAYS = [
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    public function handle(Project $project, string $dateStart, string $dateEnd, int $hoursPerDay): array
    {
        $start            = CarbonImmutable::parse($dateStart);
        $end              = CarbonImmutable::parse($dateEnd);
        $excludedWeekdays = $project->excluded_weekdays ?? [];
        $days             = $this->countWorkingDays($start, $end, $excludedWeekdays);
        $hours            = $days * $hoursPerDay;

        return [
            'project'          => $project->name,
            'start'            => $start->format('d/m/Y'),
            'end'              => $end->format('d/m/Y'),
            'excludedWeekdays' => array_values(array_intersect_key(self::WEEKDAYS, array_flip($excludedWeekdays))),
            'days'             => $days,
            'hoursPerDay'      => $hoursPerDay,
            'hours'            => $hours,
            'hourlyRate'       => $project->formatted_price_per_hour,
            'total'            => 'R$ ' . $project->formatMoney((float) $project->price_per_hour * $hours),
        ];
    }

    private function countWorkingDays(CarbonImmutable $start, CarbonImmutable $end, array $excludedWeekdays): int
    {
        $calendarDays   = (int) $start->diffInDays($end) + 1;
        $fullWeeks      = intdiv($calendarDays, 7);
        $days           = $fullWeeks * (7 - count($excludedWeekdays));
        $remainingStart = $start->addWeeks($fullWeeks);

        if ($remainingStart > $end) {
            return $days;
        }

        foreach ($remainingStart->daysUntil($end) as $date) {
            if (!in_array($date->dayOfWeekIso, $excludedWeekdays, true)) {
                $days++;
            }
        }

        return $days;
    }
}
