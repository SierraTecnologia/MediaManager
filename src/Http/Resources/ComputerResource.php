<?php

namespace MediaManager\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComputerResource extends JsonResource
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
        return [
            'id' => $this->id,
            'token' => $this->token,
            'name' => $this->name,
            'group' => new GroupResource($this->group),
            'videos' => (empty($videos = $this->getVideosToPlay()) || $videos->isEmpty())?[]:VideoResource::collection($videos),
            'description' => $this->description,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
