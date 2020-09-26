<?php

namespace MediaManager\Entities\Uploads;

use Population\Models\Components\Book\Page;
use Support\Models\Ownable;

class Attachment extends Ownable
{


    /**
     * Get the downloadable file name for this upload.
     *
     * @return mixed|string
     */
    public function getFileName()
    {
        if (str_contains($this->name, '.')) {
            return $this->name;
        }
        return $this->name . '.' . $this->extension;
    }
}
