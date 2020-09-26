<?php

namespace MediaManager\Repositories;

use Cms;
use Config;
use CryptoService;
use Informate\Models\Tag;
use Muleta\Repositories\BaseRepository;
use MediaManager\Models\Imagen as Image;
use MediaManager\Services\FileService;


class ImageRepository extends BaseRepository
{
    public Image $model;

    public string $table;

    public function published()
    {
        return $this->model->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(Config::get('cms.pagination', 24));
    }

    /**
     * Returns all Images for the API.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function apiPrepared()
    {
        return $this->model->orderBy('created_at', 'desc')->where('is_published', 1)->get();
    }

    /**
     * Stores Images into database.
     *
     * @param array $input
     *
     * @return Images
     */
    public function apiStore($input)
    {
        $savedFile = app(FileService::class)->saveClone($input['location'], 'public/images');

        if (!$savedFile) {
            return false;
        }

        $input['is_published'] = 1;
        $input['location'] = $savedFile['name'];
        $input['storage_location'] = \Illuminate\Support\Facades\Config::get('cms.storage-location');
        $input['original_name'] = $savedFile['original'];

        $image = $this->model->create($input);
        $image->setCaches();

        return $image;
    }

    /**
     * Stores Images into database.
     *
     * @param array $input
     *
     * @return Images
     */
    public function store($input)
    {
        $savedFile = $input['location'];

        if (!$savedFile) {
            Cms::notification('Image could not be saved.', 'danger');

            return false;
        }

        if (!isset($input['is_published'])) {
            $input['is_published'] = 0;
        } else {
            $input['is_published'] = 1;
        }

        $input['location'] = CryptoService::decrypt($savedFile['name']);
        $input['storage_location'] = \Illuminate\Support\Facades\Config::get('cms.storage-location');
        $input['original_name'] = $savedFile['original'];
        $input['tags'] = explode(',', $input['tags']);
        $image = $this->model->create($input);
        $image->setCaches();

        return $image;
    }

    /**
     * Updates Images
     *
     * @param Images $images
     * @param array  $payload
     *
     * @return Images
     */
    public function update($model, $payload)
    {
        if (isset($payload['location']) && !empty($payload['location'])) {
            $savedFile = app(FileService::class)->saveFile($payload['location'], 'public/images', [], true);

            if (!$savedFile) {
                Cms::notification('Image could not be updated.', 'danger');

                return false;
            }

            $payload['location'] = $savedFile['name'];
            $payload['original_name'] = $savedFile['original'];
        } else {
            $payload['location'] = $model->location;
        }

        if (!isset($payload['is_published'])) {
            $payload['is_published'] = 0;
        } else {
            $payload['is_published'] = 1;
        }

        $model->forgetCache();

        $model->update($payload);

        $model->setCaches();

        return $model;
    }
}
