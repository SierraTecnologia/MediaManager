<?php

namespace MediaManager\Http\Resources;

class VideoResource extends MediaResource
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
            'name' => $this->name,
            'description' => $this->description,
            'url' => $this->getLink(),
            'type' => $this->type,
            'filename' => $this->filename,
            'size' => $this->size,
            'last_modified' => $this->last_modified,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at
        ];
    }
}
