<?php

namespace App\Http\Controllers;

use App\Models\TeacherCalendars;
use App\Services\Zipper;
use DirectoryIterator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class XMLUploadController extends BaseController
{
    public function upload(Request $request)
    {
        if (!$request->file('xmlFile')->isValid()) {
            return response()->withErrors(['error' => 'File is not valid'], 400);
        }

        try {
            $xml = $this->xmlFromRequest($request);
        } catch (\Exception $e) {
            return back()->withErrors('Could not load xml file');
        }

        $errors = (new TeacherCalendars($xml))->build();
        if (count($errors)) {
            return back()->withErrors($errors);
        }
        $pathToZip = storage_path('app/calendars');
        $zipPath = Zipper::zip($pathToZip);

        array_map('unlink', glob("{$pathToZip}/*.*"));
        dispatch(fn() => unlink($zipPath))->afterResponse();

        return response()->download($zipPath, now()->toDateTimeString().'-calendars.zip');
    }

    protected function xmlFromRequest(Request $request): \SimpleXMLElement
    {
        return simplexml_load_string($request->file('xmlFile')->getContent());
    }
}
