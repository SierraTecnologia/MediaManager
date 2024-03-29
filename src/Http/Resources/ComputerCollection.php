<?php

namespace MediaManager\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ComputerCollection extends ResourceCollection
{
    
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = ComputerResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}