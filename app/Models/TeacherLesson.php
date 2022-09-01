<?php

namespace App\Models;

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
