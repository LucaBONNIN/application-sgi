<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\VacationPeriodFactory;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VacationPeriod extends Model implements Eventable, Auditable
{
    /** @use HasFactory<VacationPeriodFactory> */
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'school_year',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'school_year' => 'integer',
        ];
    }

    /**
     * @param  Builder<VacationPeriod>  $query
     * @return Builder<VacationPeriod>
     */
    public function scopeOverlapping(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->where('start_date', '<=', $end)
            ->where('end_date', '>=', $start);
    }

    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title($this->name)
            ->start($this->start_date)
            ->end($this->end_date)
            ->allDay();
    }
}
