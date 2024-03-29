<?php

namespace MediaManager\Models;

use MediaManager\Builders\ThumbnailBuilder;
use MediaManager\Entities\ThumbnailEntity;
use MediaManager\Models\Model as Base;

/**
 * Class Thumbnail.
 *
 * @property int id
 * @property string path
 * @property int width
 * @property int height
 * @package  MediaManager\Models
 */
class Thumbnail extends Base
{
    public static $classeBuilder = ThumbnailBuilder::class;
    /**
     * @inheritdoc
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'path',
        'width',
        'height',
    ];

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): ThumbnailBuilder
    {
        return new ThumbnailBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): ThumbnailBuilder
    {
        return parent::newQuery();
    }

    public function thumbnailable()
    {
        return $this->morphTo();
    }

    /**
     * @return ThumbnailEntity
     */
    public function toEntity(): ThumbnailEntity
    {
        return new ThumbnailEntity(
            [
            'path' => $this->path,
            'width' => $this->width,
            'height' => $this->height,
            ]
        );
    }
}
