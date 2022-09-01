<?php


namespace App\Models;

use Illuminate\Support\Collection;

class CalendarsXMLLoader
{
    public static function load(string $contents): Collection
    {
        $teachersCalendarsXML = static::xmlFromRequest($contents);
        return recordsToCollection($teachersCalendarsXML, 3);
    }

    protected static function xmlFromRequest($contents): \SimpleXMLElement
    {
        return simplexml_load_string($contents);
    }
}
