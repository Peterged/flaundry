<?php
class DownloadService
{
    public static function download($path, $filename = null)
    {
        if (is_null($filename)) {
            $filename = basename($path);
        }
        $file = $path;
        $mimeType = mime_content_type($file);
        $fileSize = filesize($file);
        header("Content-Type: " . $mimeType);
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filename);
        readfile($file);
    }

    public static function downloadBlob($FILE, string $filename = null)
    {
        if (is_null($filename)) {
            $filename = basename($FILE);
        }
        $file = $FILE;
        $mimeType = mime_content_type($file);
        $fileSize = filesize($file);
        header("Content-Type: " . $mimeType);
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filename);
        readfile($file);
    }
}
