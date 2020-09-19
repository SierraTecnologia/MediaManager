<?php

namespace MediaManager\Http\Controllers;

use Config;
use MediaManager\Repositories\ImageRepository;
use Templeiro;

class GalleryController extends BaseController
{
    protected $repository;
    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
        // $this->middleware('subscription');
    }

    /**
     * Display page list.
     *
     * @return Response
     */
    public function all()
    {
        $images = $this->repository->published();
        $tags = $this->repository->allTags();

        if (empty($images)) {
            abort(404);
        }

        return Templeiro::populateView('midia.gallery.all',
            [
                'tags' => $tags,
                'images' => $images
            ]
        );
    }

    /**
     * PreviewImage
     *
     * @return Response
     */
    public function imagePreview()
    {
        $images = $this->repository->published();
        $tags = $this->repository->allTags();

        if (empty($images)) {
            abort(404);
        }

        return Templeiro::populateView('midia.gallery.all',
            [
                'tags' => $tags,
                'images' => $images
            ]
        );
    }

    /**
     * Display the specified Gallery.
     *
     * @param string $url
     *
     * @return Response
     */
    public function show($tag)
    {
        $images = $this->repository->getImagesByTag($tag)->paginate(Config::get('cms.pagination'));
        $tags = $this->repository->allTags();

        if (empty($images)) {
            abort(404);
        }

        return Templeiro::populateView('midia.gallery.show',
            [
                'title' => $tag,
                'tags' => $tags,
                'images' => $images
            ]
        );
    }
}
