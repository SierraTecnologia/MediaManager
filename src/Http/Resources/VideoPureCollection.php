<?php

namespace MediaManager\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoPureCollection extends ResourceCollection
{
    
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = VideoPureResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
