<?php

namespace App\Exports;

use App\Models\TeacherCalendar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CalendarsExport implements FromCollection, WithMultipleSheets
{
    use Exportable;

    public function __construct(protected Collection $calendars)
    {
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->calendar;
    }

    public function sheets(): array
    {
        return $this->calendars->map(fn($calendar, $teacher) => new CalendarExportSheet($teacher, $calendar))->all();
    }
}
