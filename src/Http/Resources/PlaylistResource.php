<?php

namespace MediaManager\Http\Resources;

use App\Models\Playlist;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
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
    // @todo Descobrir CAmpo Group e Brand_id e preencher
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'is_active'         => $this->is_active,
            'status'            => $this->status,
            'videos'            => VideoResource::collection($this->videos),
            'created_at'        => (string) $this->created_at,
            'updated_at'        => (string) $this->updated_at
        ];
    }
}
