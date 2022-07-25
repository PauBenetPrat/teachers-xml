<?php

namespace App\Models;

use App\Exceptions\TeacherException;

class TeacherCalendar
{
    public static array $days = [
        'Dilluns',
        'Dimarts',
        'Dimecres',
        'Dijous',
        'Divendres',
    ];
    public static array $hours = [
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
    protected string $teacher;

    public function __construct(protected \SimpleXMLElement $calendar) {
        $this->teacher = $this->calendar->attributes();
    }

    /**
     * @throws TeacherException
     */
    public function create()
    {
        $fp = fopen(storage_path("{$this->teacher}.csv"), 'w');
//        $fp = fopen(storage_path("app/calendars/{$this->teacher}.csv"), 'w');
        fputcsv($fp, ["Professor/a: {$this->teacher}"]);
        fputcsv($fp, ['', ...self::$days]);
        foreach (self::$hours as $hour) {
            $hours = [$hour];
            foreach (self::$days as $day) {
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

    /**
     * @throws TeacherException
     */
    protected function lesson(string $day, string $hour): \SimpleXMLElement
    {
        try {
            return $this->calendar->xpath("Day[@name='{$day}']/Hour[@name='{$hour}']")[0];
        } catch (\Throwable $e) {
            throw new TeacherException("No lesson found for {$this->teacher} at {$day} {$hour}");
        }
    }
}
