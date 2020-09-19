<?php

namespace MediaManager\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class ThumbnailBuilder.
 *
 * @package MediaManager\Builders
 */
class ThumbnailBuilder extends Builder
{
    /**
     * @return $this
     */
    public function whereHasNoPhotos()
    {
        return $this->doesntHave('photos');
    }
}
