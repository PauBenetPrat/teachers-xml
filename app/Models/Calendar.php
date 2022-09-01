<?php


namespace App\Models;

use App\Exceptions\CalendarException;
use Illuminate\Support\Collection;

class Calendar
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

    public function __construct(protected string $person, protected Collection $calendar, protected bool $asSubgroup = false)
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
     * @throws CalendarException
     */
    protected function lesson(string $day, string $hour): Lesson
    {
        try {
            return $this->asSubgroup
                ? (new SubgroupLesson($this->calendar, $day, $hour))
                : (new TeacherLesson($this->calendar, $day, $hour));
        } catch (\Throwable $e) {
            throw new CalendarException("No lesson found for {$this->person} at {$day} {$hour}");
        }
    }
}
