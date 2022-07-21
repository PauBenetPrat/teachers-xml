<?php


namespace App\Console\Commands;

class TeacherCalendars
{
    public function __construct(protected \SimpleXMLElement $teachers)
    {
    }

    public function dispatch()
    {
        foreach ($this->teachers as $teacher) {
            (new Calendar($teacher))->create();
        }
    }
}
