<?php


namespace App\Models\Lessons;

class GroupLesson extends Lesson
{
    public function title(array $lesson): string
    {
        if (! isset($lesson['Teacher'])) {
            return '';
        }
        return "{$lesson['Subject']} - {$lesson['Teacher']}";
    }
}
