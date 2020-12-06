<?php

namespace MediaManager\Models;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    // public $table = 'media';

    // public $primaryKey = 'id';

    // protected $guarded = [];

    // public $rules = [
    //     'location' => 'required',
    // ];


    public function thumbnails()
    {
        return $this->morphMany(Thumbnail::class, 'thumbnailable')
        ->orderBy('width')
        ->orderBy('height');
    }

}
