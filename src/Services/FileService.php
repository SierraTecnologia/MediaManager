<?php

namespace MediaManager\Services;

use Crypto as CryptoServiceForFiles;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FileService
{


    /**
     * Saves File.
     *
     * @param string $fileName File input name
     * @param string $location Storage location
     *
     * @return string[]
     *
     * @psalm-return array{original: string, name: string}
     */
    public static function saveClone($fileName, $directory = '', $fileTypes = []): array
    {
        $fileInfo = pathinfo($fileName);

        if (substr($directory, 0, -1) != '/') {
            $directory .= '/';
        }

        $extension = $fileInfo['extension'];
        $newFileName = md5(rand(1111, 9999).time());

        // In case we don't want that file type
        if (!empty($fileTypes)) {
            if (!in_array($extension, $fileTypes)) {
                throw new Exception('Incorrect file type', 1);
            }
        }

        Storage::disk(Config::get('cms.storage-location', 'local'))->put($directory.$newFileName.'.'.$extension, file_get_contents($fileName));

        return [
            'original' => basename($fileName),
            'name' => $directory.$newFileName.'.'.$extension,
        ];
    }

    /**
     * Saves File.
     *
     * @param string $fileName File input name
     * @param string $location Storage location
     *
     * @return (mixed|string)[]|false
     *
     * @psalm-return array{original: mixed|string, name: string}|false
     */
    public static function saveFile($fileName, $directory = '', $fileTypes = [])
    {
        if (is_object($fileName)) {
            $file = $fileName;
            $originalName = $file->getClientOriginalName();
        } else {
            $file = Request::file($fileName);
            $originalName = false;
        }

        if (is_null($file)) {
            return false;
        }

        if (File::size($file) > Config::get('cms.max-file-upload-size', 6291456)) {
            throw new Exception('This file is too large', 1);
        }

        if (substr($directory, 0, -1) != '/') {
            $directory .= '/';
        }

        $extension = $file->getClientOriginalExtension();
        $newFileName = md5(rand(1111, 9999).time());

        // In case we don't want that file type
        if (!empty($fileTypes)) {
            if (!in_array($extension, $fileTypes)) {
                throw new Exception('Incorrect file type', 1);
            }
        }

        Storage::disk(Config::get('cms.storage-location', 'local'))->put($directory.$newFileName.'.'.$extension, File::get($file));

        return [
            'original' => $originalName ?: $file->getFilename().'.'.$extension,
            'name' => $directory.$newFileName.'.'.$extension,
        ];
    }
}
