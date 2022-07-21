<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Console\Commands\TeacherCalendars;
use Tests\TestCase;

class TeacherCalendarsTest extends TestCase
{
    /** @test */
    public function creates_calendars_for_teachers_xml()
    {
        $teachers = simplexml_load_file("storage/app/teachers.xml") or die("Failed to load");

        (new TeacherCalendars($teachers))->dispatch();

        self::assertTrue(true);
    }
}
