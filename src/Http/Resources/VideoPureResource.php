<?php

namespace MediaManager\Http\Resources;

class VideoPureResource extends MediaResource
{
    // public $with = [
    //     'success' => true
    // ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getLink();
    }
}
