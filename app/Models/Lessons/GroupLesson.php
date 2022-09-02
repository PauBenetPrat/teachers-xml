<?php


namespace App\Models\Lessons;

class GroupLesson extends Lesson
{
    public function title(array $lesson): string
    {
        if (! $teachers = $lesson['Teacher'] ?? null) {
            return $lesson['Subject'] ?? '';
        }
        return "{$teachers} - {$lesson['Subject']}";
    }
}
