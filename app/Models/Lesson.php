<?php


namespace App\Models;

use Illuminate\Support\Collection;

class Lesson
{
    public string $title;

    public function __construct(Collection $calendar, string $day, string $hour)
    {
        $this->title = $this->title($calendar[$day][$hour]);
    }

    public function title(array $lesson): string
    {
        if (! $students = $lesson['Students'] ?? null) {
            return $lesson['Subject'] ?? '';
        }

        return "{$students} - {$lesson['Subject']}";
    }

}
