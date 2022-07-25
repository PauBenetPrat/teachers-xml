<?php


namespace App\Services;

use DirectoryIterator;

class Zipper
{
    public static function zip(string $calendarsPath, string $name = null): string
    {
        $zipPath = storage_path($name ?? (uniqid().'.zip'));

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $dir = new DirectoryIterator($calendarsPath);
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                $zip->addFile($file->getRealPath(), $file->getFilename());
            }
        }
        $zip->close();

        return $zipPath;
    }
}
