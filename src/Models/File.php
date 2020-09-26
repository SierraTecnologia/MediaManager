<?php

namespace MediaManager\Models;

use Crypto;

class File extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'url',
        'path',
        'type',
        'filename',
        'size',
        'last_modified',
    ];

    /**
     * @var string[][]
     *
     * @psalm-var array{name: array{type: string, analyzer: string}}
     */
    protected array $mappingProperties = array(
        /**
         * User Info
         */
        'name' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
    );

        
    // // /**
    // //  * Get all of the owning videoable models.
    // //  */
    // // @todo Verificar Depois
    // public function videoable()
    // {
    //     return $this->morphTo(); //, 'videoable_type', 'videoable_code'
    // }

    public function getLink()
    {
        return $this->url; // @todo
        // return route('asset.show', [
        //     'path' => Crypto::urlEncode($this->path),
        //     'contentType' => Crypto::urlEncode($this->type),
        // ]);
        // return route('asset.public', ['encFileName' => Crypto::urlEncode($this->path)]);
        // return url('public-preview/'.Crypto::urlEncode($this->path));
        // Crypto::urlEncode($this->url);
        // Crypto::urlEncode($this->path);
        // Crypto::urlEncode($this->type);
        // // Crypto::urlDecode($this->);
    }
}
