<?php

namespace App\Models;

use App\Exceptions\CalendarException;
use App\Services\Zipper;
use Illuminate\Support\Collection;

class Calendars
{
    public array $errors = [];
    public Collection $collection;

    public function __construct(Collection $people, protected bool $asSubgroup = false)
    {
        $this->collection = $this->collection($people);
    }

    protected function collection(Collection $people): Collection
    {
        return $people->mapWithKeys(function ($calendar, $person) {
            try {
                return [$person => (new Calendar($person, $calendar, $this->asSubgroup))->collection()];
            } catch (CalendarException $e) {
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
        $this->collection->each(function ($calendar, $person) {
            $directoryPath = storage_path('app/calendars/');
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            $fp = fopen("{$directoryPath}/{$person}.csv", 'w');
            fputcsv($fp, [
                ($this->asSubgroup ? "Subgrup " : "Professor/a: ") . $person
            ]);
            fputcsv($fp, ['', ...Calendar::$days]);
            $calendar->each(function (array $hour) use ($fp) {
                fputcsv($fp, $hour);
            });
            fclose($fp);
        });
    }
}
