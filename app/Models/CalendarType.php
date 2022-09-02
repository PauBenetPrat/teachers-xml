<?php

namespace App\Models;

use App\Models\Lessons\Lesson;
use Illuminate\Support\Collection;

interface CalendarType
{
    public function header(string $person): string;
    public function lesson(Collection $calendar, string $day, string $hour): Lesson;
}
