<?php

namespace App\Filament\App\Resources\VacationPeriods\Widgets;

use App\Models\VacationPeriod;
use Filament\Widgets\Widget;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\ValueObjects\DateClickInfo;

class MyCalendarWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;

    //Activer le clic sur une date dans le widget :
    protected bool $dateClickEnabled = true;

    //Créer l'action de création dans le widget :
    public function createVacationPeriodAction(): CreateAction
    {
        return $this->createAction(VacationPeriod::class);
    }

    //Monter l'action quand on clique sur une date
    protected function onDateClick(DateClickInfo $info): void
    {
        $this->mountAction('createVacationPeriod');
    }

    public function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return VacationPeriod::query()

            ->whereDate('end_date', '>=', $info->start)
            ->whereDate('start_date', '<=', $info->end)
            ->get();
    }
}
