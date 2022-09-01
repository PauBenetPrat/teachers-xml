<?php

namespace App\Exports;

use App\Models\Calendar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class CalendarExportSheet implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;

    public function __construct(protected string $person, protected Collection $calendar, protected bool $asSubgroup = false)
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
            [($this->asSubgroup ? "Subgrup " : "Professor/a: ") . $this->person],
            ['', ...Calendar::$days],
        ];
    }

    public function title(): string
    {
        return $this->person;
    }
}
