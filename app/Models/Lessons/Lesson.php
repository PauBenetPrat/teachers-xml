<?php


namespace App\Models\Lessons;

use Illuminate\Support\Collection;

abstract class Lesson
{
    public string $title;

    public function __construct(Collection $calendar, string $day, string $hour)
    {
        $this->title = $this->title($calendar[$day][$hour]);
    }

    abstract public function title(array $lesson): string;
}
