<?php

namespace App\Models\Lessons;

class TeacherLesson extends Lesson
{
    public function title(array $lesson): string
    {
        if (! $students = $lesson['Students'] ?? null) {
            return $lesson['Subject'] ?? '';
        }

        return "{$students} - {$lesson['Subject']}";
    }
}
