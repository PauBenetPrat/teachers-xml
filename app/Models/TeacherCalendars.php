<?php

namespace App\Models;

use App\Exceptions\TeacherException;

class TeacherCalendars
{
    public function __construct(protected \SimpleXMLElement $teachers)
    {
    }

    public function build(): array
    {
        $errors = [];
        foreach ($this->teachers as $teacher) {
            try {
                (new TeacherCalendar($teacher))->create();
            } catch (TeacherException $e) {
                $errors[] = $e->getMessage();
            }
        }
        return $errors;
    }
}
