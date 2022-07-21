<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\Calendar;
use App\Console\Commands\TeacherCalendars;
use Tests\TestCase;

class ParseXMLTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $teachers = simplexml_load_file("tests/Feature/fixtures/teacher.xml") or die("Failed to load");

        (new TeacherCalendars($teachers))->dispatch();

        self::assertTrue(true);
    }

    protected function createCalender(\SimpleXMLElement|bool|null $teacher)
    {
        if (!$teacher instanceof \SimpleXMLElement) {
            dump('Invalid XML');
            dump($teacher);
            return;
        }

        foreach ($teacher->Day as $weekDay) {
            $weekDay = $teacher->attributes()['name'];
            foreach ($weekDay->Hour as $hour) {
                dd($weekDay);
            }
        }
        $fp = fopen("{$teacher->attributes()['name']}-calendar.csv", 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
}
