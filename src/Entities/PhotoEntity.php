<?php

namespace MediaManager\Entities;

use Carbon\Carbon;
use MediaManager\ValueObjects\ImageMetadata;
use Illuminate\Support\Collection;

/**
 * Class PhotoEntity.
 *
 * @package Core\Entities
 */
final class PhotoEntity extends AbstractEntity
{
    private $id;

    private $path;




    private $thumbnails;
    private $location;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getPath();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'created_by_user_id' => $this->getCreatedByUserId(),
            'path' => $this->getPath(),
            'avg_color' => $this->getAvgColor(),
            'metadata' => $this->getMetadata()->toArray(),
            'created_at' => $this->getCreatedAt() ? $this->getCreatedAt()->toAtomString() : null,
            'updated_at' => $this->getUpdatedAt() ? $this->getUpdatedAt()->toAtomString() : null,
            'location' => $this->getLocation() ? $this->getLocation()->toArray() : null,
            'thumbnails' => $this->getThumbnails()->toArray(),
        ];
    }
}
