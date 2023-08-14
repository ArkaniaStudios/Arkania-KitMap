<?php
declare(strict_types=1);

namespace arkania\path;

use InvalidArgumentException;
use ZipArchive;

class FileSystem {

    public static function deleteRecursiveDir(string $dir) : void {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException("$dir must be a directory");
        }
        if (!str_ends_with($dir, '/')) {
            $dir .= '/';
        }
        $files = glob($dir . '*', GLOB_MARK);
        if ($files === false) {
            return;
        }
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteRecursiveDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    public static function unZipFile(string $path) : bool {
        $zipFilePath = $path . '.zip';
        $extractPath = $path;
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

}