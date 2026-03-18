<?php

namespace App\Services;

use App\Models\VacationPeriod;
use Carbon\Carbon;

class OrderAgeCalculator
{
    /**
     * Calculate the number of business days between two dates,
     * excluding weekends (Saturday, Sunday) and school vacation periods.
     */
    public function calculateBusinessDays(Carbon $from, Carbon $to): int
    {
        if ($from->greaterThanOrEqualTo($to)) {
            return 0;
        }

        $vacationPeriods = VacationPeriod::query()
            ->overlapping($from, $to)
            ->get(['start_date', 'end_date']);

        $businessDays = 0;
        $current = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();

        while ($current->lessThan($end)) {
            if (! $current->isWeekend() && ! $this->isInVacation($current, $vacationPeriods)) {
                $businessDays++;
            }

            $current->addDay();
        }

        return $businessDays;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Collection<int, VacationPeriod>  $vacationPeriods
     */
    private function isInVacation(Carbon $date, $vacationPeriods): bool
    {
        foreach ($vacationPeriods as $period) {
            if ($date->between($period->start_date, $period->end_date)) {
                return true;
            }
        }

        return false;
    }
}
