<?php


namespace App\Console\Commands;

class Calendar
{
    protected $days = [
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
        $teacherName = $this->teacher->attributes()['name'];
        $fp = fopen("storage/app/calendars/{$teacherName}.csv", 'w');
        fputcsv($fp, ["Professor/a: {$teacherName}"]);
        fputcsv($fp, ['', ...$this->days]);
        foreach ($this->hours as $hourIndex => $hour) {
            $hours = [$hour];
            foreach ($this->days as $dayIndex => $day) {
                $hours[] = $this->title($this->teacher->Day[$dayIndex]->Hour[$hourIndex]);
            }
            fputcsv($fp, $hours);
        }
        fclose($fp);
    }

    protected function title(\SimpleXMLElement $lesson): string
    {
        if (! $students = $lesson->Students->attributes()['name'] ?? null) {
            return $lesson->Subject->attributes()['name'] ?? '';
        }
        return "{$students} - " . $lesson->Subject->attributes()['name'] ?? '';
    }
}
