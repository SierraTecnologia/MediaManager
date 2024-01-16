<?php

namespace MediaManager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComputerPureResource extends JsonResource
{
    public $with = [
        'success' => true
    ];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return (empty($videos = $this->getVideosToPlay()) || $videos->isEmpty())?[]:VideoPureResource::collection($videos);
    }
}
