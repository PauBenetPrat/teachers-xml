<?php


namespace App\Models;

class SubgroupLesson extends Lesson
{
    public function title(array $lesson): string
    {
        if (! $teachers = $lesson['Teacher'] ?? null) {
            return $lesson['Subject'] ?? '';
        }
        return "{$teachers} - {$lesson['Subject']}";
    }
}
