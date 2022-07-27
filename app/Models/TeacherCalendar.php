<?php

namespace App\Models;

use App\Exceptions\TeacherException;
use Illuminate\Support\Collection;
use function PHPUnit\Framework\directoryExists;

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

    public function __construct(protected string $teacher, protected Collection $calendar)
    {
    }

    public function collection(): Collection
    {
        return collect(self::$hours)->map(
            fn ($hour) => [
                $hour,
                ...collect(self::$days)->map(
                    fn ($day) => $this->lesson($day, $hour)->title
                )
            ]
        );
    }

    /**
     * @throws TeacherException
     */
    protected function lesson(string $day, string $hour): Lesson
    {
        try {
            return (new Lesson($this->calendar, $day, $hour));
        } catch (\Throwable $e) {
            throw new TeacherException("No lesson found for {$this->teacher} at {$day} {$hour}");
        }
    }
}
