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
//        $this->sendMail($request->email, $zipPath);
        dispatch(fn() => unlink($zipPath))->afterResponse();

        return response()->download($zipPath, now()->toDateTimeString().'-calendars.zip');
    }

    protected function zipFile(): string
    {
        $zip_file = public_path('storage/'.uniqid().'-teachers.zip');

        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $dir = new DirectoryIterator(storage_path('app/calendars'));
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $zip->addFile($fileinfo->getRealPath(), $fileinfo->getFilename());
            }
        }
        $zip->close();

        return $zip_file;
    }

    protected function sendMail(string $email, string $zipPath): void
    {
        Mail::html('Attached calendars', function ($message) use ($email, $zipPath) {
            $message->to($email)
                ->subject('Calendars');
            $message->attach($zipPath, [
                'as'   => now()->format('YmdHis').'-calendars.zip',
                'mime' => 'application/zip',
            ]);
        });
    }

    protected function xmlFromRequest(Request $request): \SimpleXMLElement
    {
        return simplexml_load_string($request->file('xmlFile')->getContent());
    }
}
