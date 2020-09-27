<?php

namespace MediaManager\Repositories;

use Auth;
use Config;
use CryptoService;
use MediaManager\Models\File;
use Muleta\Repositories\BaseRepository;
use MediaManager\Services\FileService;


class FileRepository extends BaseRepository
{
    public File $model;

    public $table;

    /**
     * Stores Files into database.
     *
     * @param array $input
     *
     * @return Files
     */
    public function store($payload)
    {
        $result = false;

        foreach ($payload['location'] as $file) {
            $filePayload = $payload;
            $filePayload['name'] = $file['original'];
            $filePayload['location'] = CryptoService::decrypt($file['name']);
            $filePayload['mime'] = $file['mime'];
            $filePayload['size'] = $file['size'];
            $filePayload['order'] = 0;
            $filePayload['user'] = (isset($payload['user'])) ? $payload['user'] : Auth::id();
            $filePayload['is_published'] = (isset($payload['is_published'])) ? (bool) $payload['is_published'] : 0;
            $result = $this->model->create($filePayload);
        }

        return $result;
    }

    /**
     * Updates Files into database.
     *
     * @param Files $model
     * @param array $payload
     *
     * @return Files
     */
    public function update($model, $payload)
    {
        if (isset($payload['location'])) {
            $savedFile = app(FileService::class)->saveFile($payload['location'], 'files/');
            $_file = $payload['location'];

            $filePayload = $payload;
            $filePayload['name'] = $savedFile['original'];
            $filePayload['location'] = $savedFile['name'];
            $filePayload['mime'] = $_file->getClientMimeType();
            $filePayload['size'] = $_file->getClientSize();
        } else {
            $filePayload = $payload;
        }

        $filePayload['is_published'] = (isset($payload['is_published'])) ? (bool) $payload['is_published'] : 0;

        return $model->update($filePayload);
    }

    /**
     * Files output for API calls
     *
     * @return (mixed|string)[][]
     *
     * @psalm-return list<array{file_identifier: string, file_name: mixed, file_date: mixed}>
     */
    public function apiPrepared(): array
    {
        $files = File::orderBy('created_at', 'desc')->where('is_published', 1)->get();
        $allFiles = [];

        foreach ($files as $file) {
            array_push(
                $allFiles, [
                'file_identifier' => CryptoService::url_encode($file->name).'/'.CryptoService::url_encode($file->location),
                'file_name' => $file->name,
                'file_date' => $file->created_at->format('F jS, Y'),
                ]
            );
        }

        return $allFiles;
    }
}
