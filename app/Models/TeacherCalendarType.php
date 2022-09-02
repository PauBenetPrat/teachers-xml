<?php


namespace App\Models;

use App\Models\Lessons\Lesson;
use App\Models\Lessons\TeacherLesson;
use Illuminate\Support\Collection;

class TeacherCalendarType implements CalendarType
{
    public function header(string $teacher): string
    {
        return "Professor/a: {$teacher}";
    }

    public function lesson(Collection $calendar, string $day, string $hour): Lesson
    {
        return new TeacherLesson($calendar, $day, $hour);
    }
}
