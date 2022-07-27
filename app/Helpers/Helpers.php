<?php

use Illuminate\Support\Collection;

function recordsToCollection(SimpleXMLElement $records, $depth = 1) : Collection
{
    $array = [];
    foreach ($records as $record) {
        $values = array_values((array)$record);
        if ($depth > 1) {
            $array[$values[0]['name']] = recordsToCollection($record, $depth - 1);
        } else {
            $array[$values[0]['name']]['name'] = "{$record->attributes()}";
            foreach ($record as $key => $lesson) {
                $array[$values[0]['name']][$key] = "{$lesson->attributes()}";
            }
        }
    }

    return collect($array);
}
