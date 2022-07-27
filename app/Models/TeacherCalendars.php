<?php

namespace App\Models;

use App\Exceptions\TeacherException;
use App\Services\Zipper;
use Illuminate\Support\Collection;

class TeacherCalendars
{
    public array $errors = [];
    public Collection $collection;

    public function __construct(Collection $teachers)
    {
        $this->collection = $this->collection($teachers);
    }

    protected function collection(Collection $teachers): Collection
    {
        return $teachers->mapWithKeys(function ($calendar, $teacher) {
            try {
                return [$teacher => (new TeacherCalendar($teacher, $calendar))->collection()];
            } catch (TeacherException $e) {
                $this->errors[] = $e->getMessage();
            }
            return [];
        })->filter();
    }

    public function getCsvsZip(): string
    {
        $this->exportCsvs();
        return $this->zip();
    }

    protected function zip(): string
    {
        $pathToZip = storage_path('app/calendars');
        $zipPath = Zipper::zip($pathToZip);
        array_map('unlink', glob("{$pathToZip}/*.*"));

        return $zipPath;
    }

    protected function exportCsvs()
    {
        $this->collection->each(function ($calendar, $teacher) {
            $directoryPath = storage_path('app/calendars/');
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $fp = fopen("{$directoryPath}/{$teacher}.csv", 'w');
            fputcsv($fp, ["Professor/a: {$teacher}"]);
            fputcsv($fp, ['', ...TeacherCalendar::$days]);
            $calendar->each(function (array $hour) use ($fp) {
                fputcsv($fp, $hour);
            });
            fclose($fp);
        });
    }
}
