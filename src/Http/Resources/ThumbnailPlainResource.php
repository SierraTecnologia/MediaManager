<?php

namespace MediaManager\Http\Resources;

use MediaManager\Entities\ThumbnailEntity;
use Illuminate\Http\Resources\Json\JsonResource as Resource;
use Illuminate\Support\Facades\Storage;
use function SiUtils\html_purify;
use function SiUtils\to_int;
use function SiUtils\to_string;
use function SiUtils\url_storage;

/**
 * Class ThumbnailPlainResource.
 *
 * @package MediaManager\Http\Resources
 */
class ThumbnailPlainResource extends Resource
{
    /**
     * @var ThumbnailEntity
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'url' => to_string(
                html_purify(
                    function () {
                        return url_storage(Storage::url($this->resource->getPath()));
                    }
                )
            ),
            'width' => to_int(html_purify($this->resource->getWidth())),
            'height' => to_int(html_purify($this->resource->getHeight())),
        ];
    }
}
