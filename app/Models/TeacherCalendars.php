<?php

namespace App\Models;

use App\Exceptions\TeacherException;
use Illuminate\Support\Collection;

class TeacherCalendars
{
    public array $errors = [];

    public function __construct(protected Collection $teachers)
    {
    }

    public function collection(): Collection
    {
        return $this->teachers->mapWithKeys(function ($calendar, $teacher) {
            try {
                return [$teacher => (new TeacherCalendar($teacher, $calendar))->collection()];
            } catch (TeacherException $e) {
                $this->errors[] = $e->getMessage();
            }
            return [];
        })->filter();
    }
}
