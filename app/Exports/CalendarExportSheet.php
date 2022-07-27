<?php

namespace App\Exports;

use App\Models\TeacherCalendar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CalendarExportSheet implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    public function __construct(protected string $teacher,protected Collection $calendar)
    {
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->calendar;
    }

    public function headings(): array
    {
        return [
            ["Professor/a: {$this->teacher}"],
            ['', ...TeacherCalendar::$days],
        ];
    }

    public function title(): string
    {
        return $this->teacher;
    }
}
