<?php

namespace MediaManager\Repositories;

use Cms;
use Config;
use CryptoService;
use Informate\Models\Tag;
use Muleta\Modules\Eloquents\Displays\RepositoryAbstract;
use MediaManager\Models\Imagen as Image;
use MediaManager\Services\FileService;


class ImageRepository extends RepositoryAbstract
{

    public $table;

    // public function __construct(Image $model)
    // {
    //     $this->model = $model;
    //     $this->table = \Illuminate\Support\Facades\Config::get('siravel.db-prefix').'images';
    // }
    public function model()
    {
        $this->table = \Illuminate\Support\Facades\Config::get('siravel.db-prefix').'images';
        return Image::class;
    }

    public function published()
    {
        return $this->model->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(Config::get('siravel.pagination', 24));
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
     * Returns all Images for the API.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getImagesByTag($tag = null)
    {
        $images = $this->model->orderBy('created_at', 'desc')->where('is_published', 1);

        if (!is_null($tag)) {
            $images->withAnyTags([$tag]);
        }

        return $images;
    }

    /**
     * Returns all Images tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function allTags()
    {
        return Tag::all();
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
        $input['storage_location'] = \Illuminate\Support\Facades\Config::get('siravel.storage-location');
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
        $input['storage_location'] = \Illuminate\Support\Facades\Config::get('siravel.storage-location');
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
     * @param array  $input
     *
     * @return Images
     */
    public function update($image, $input)
    {
        if (isset($input['location']) && !empty($input['location'])) {
            $savedFile = app(FileService::class)->saveFile($input['location'], 'public/images', [], true);

            if (!$savedFile) {
                Cms::notification('Image could not be updated.', 'danger');

                return false;
            }

            $input['location'] = $savedFile['name'];
            $input['original_name'] = $savedFile['original'];
        } else {
            $input['location'] = $image->location;
        }

        if (!isset($input['is_published'])) {
            $input['is_published'] = 0;
        } else {
            $input['is_published'] = 1;
        }

        $image->forgetCache();

        $image->update($input);

        $image->setCaches();

        return $image;
    }
}
