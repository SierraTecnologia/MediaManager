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
 * @package  App\Models
 */
class Thumbnail extends Base
{
    /**
     * @var ThumbnailBuilder::class
     */
    public static string $classeBuilder = ThumbnailBuilder::class;
    /**
     * @inheritdoc
     *
     * @var false
     */
    public $timestamps = false;

    /**
     * @inheritdoc
     *
     * @var string[]
     *
     * @psalm-var array{0: string, 1: string, 2: string}
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function photos()
    {
        return $this->belongsToMany(Photo::class, 'thumbnails');
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
