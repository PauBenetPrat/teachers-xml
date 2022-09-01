<?php

namespace App\Http\Controllers;

use App\Exports\CalendarsExport;
use App\Models\Calendars;
use App\Models\CalendarsXMLLoader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Maatwebsite\Excel\Excel;

class CalendarsController extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function export(Request $request)
    {
        if (!$request->file('xmlFile')->isValid()) {
            return response()->withErrors(['error' => 'File is not valid'], 400);
        }

        try {
            $xml = CalendarsXMLLoader::load($request->file('xmlFile')->getContent());
        } catch (\Exception $e) {
            return back()->withErrors('Could not load xml file');
        }

        $calendars = (new Calendars($xml, $request->has('asSubgroup')));
        if (count($calendars->errors)) {
            return back()->withErrors($calendars->errors);
        }

        if ($request->has('toCsv')) {
            $zipPath = $calendars->getCsvsZip();
            dispatch(fn() => unlink($zipPath))->afterResponse();

            return response()->download($zipPath, now()->toDateTimeString().'-calendars.zip');
        }
        $exporter = new CalendarsExport($calendars->collection, $request->has('asSubgroup'));
//        return $exporter->raw(Excel::CSV);
        return $exporter->download(now()->toDateTimeString().'.xlsx', Excel::XLSX);
    }
}
