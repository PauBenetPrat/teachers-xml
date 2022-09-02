<?php


namespace App\Models;

use App\Models\Lessons\GroupLesson;
use App\Models\Lessons\Lesson;
use Illuminate\Support\Collection;

class GroupCalendarType implements CalendarType
{
    public function header(string $group): string
    {
        return "Subgrup: {$group}";
    }

    public function lesson(Collection $calendar, string $day, string $hour): Lesson
    {
        return new GroupLesson($calendar, $day, $hour);
    }
}
