<?php

namespace Tests\Feature;

use App\Models\Calendars;
use Tests\TestCase;

class TeacherCalendarsTest extends TestCase
{
    /** @test */
    public function creates_calendars_for_teachers_xml()
    {
        $teachers = simplexml_load_file("storage/app/teachers.xml") or die("Failed to load");

        $errors = (new Calendars($teachers))->build();

        self::assertCount(0, $errors);
    }
}
