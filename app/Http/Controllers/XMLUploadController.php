<?php

namespace App\Http\Controllers;

use App\Models\TeacherCalendars;
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
        $zipPath = $this->zipFile();
        dispatch(fn() => unlink($zipPath))->afterResponse();

        return response()->download($zipPath, now()->toDateTimeString().'-calendars.zip');
    }

    protected function zipFile(): string
    {
        $zipPath = storage_path(uniqid().'-teachers.zip');
        $calendarsPath = storage_path('app/calendars/');

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $dir = new DirectoryIterator($calendarsPath);
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
        }
        $zip->close();
        rmdir($calendarsPath);

        return $zipPath;
    }

    protected function xmlFromRequest(Request $request): \SimpleXMLElement
    {
        return simplexml_load_string($request->file('xmlFile')->getContent());
    }
}
