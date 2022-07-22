<?php

namespace App\Console\Commands;

class TeacherCalendar
{
    protected array $days = [
        'Dilluns',
        'Dimarts',
        'Dimecres',
        'Dijous',
        'Divendres',
    ];
    protected array $hours = [
        '8:00 - 9:00',
        '9:00 - 10:00',
        '10:00 - 11:00',
        '11:15 - 12:15',
        '12:15 - 13:15',
        '13:15 - 14:15',
        'Dinar',
        '15:00 - 16:00',
        '16:00 - 17:00',
    ];

    public function __construct(protected \SimpleXMLElement $teacher) {}

    public function create()
    {
        $teacher = $this->teacher->attributes();
        $fp = fopen(storage_path("app/calendars/{$teacher}.csv"), 'w');
        fputcsv($fp, ["Professor/a: {$teacher}"]);
        fputcsv($fp, ['', ...$this->days]);
        foreach ($this->hours as $hour) {
            $hours = [$hour];
            foreach ($this->days as $day) {
                $hours[] = $this->title($this->lesson($day, $hour));
            }
            fputcsv($fp, $hours);
        }
        fclose($fp);
    }

    protected function title(\SimpleXMLElement $lesson): string
    {
        if (! $students = $lesson->Students->attributes()) {
            return $lesson->Subject->attributes() ?? '';
        }

        return "{$students} - {$lesson->Subject->attributes()}";
    }

    protected function lesson(string $day, string $hour): \SimpleXMLElement
    {
        return $this->teacher->xpath("Day[@name='{$day}']/Hour[@name='{$hour}']")[0];
    }
}
