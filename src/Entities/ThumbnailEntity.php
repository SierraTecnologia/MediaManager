<?php

namespace MediaManager\Entities;

/**
 * Class ThumbnailEntity.
 *
 * @package Core\Entities
 */
final class ThumbnailEntity extends AbstractEntity
{
    private $path;
    private $width;
    private $height;

    /**
     * ThumbnailEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setPath($attributes['path'] ?? null);
        $this->setWidth($attributes['width'] ?? null);
        $this->setHeight($attributes['height'] ?? null);
    }

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
            'path' => $this->getPath(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}
