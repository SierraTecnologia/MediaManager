<?php

namespace MediaManager\Models;

use Crypto;
use MediaManager\Models\Model as Base;
use Muleta\Traits\Uuid;

class File extends Model
{
    use Uuid;

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
        'filename',
        'title',
        'extension',
        'size',
        'mime',
        'hash',
    ];

    protected $mappingProperties = array(
        /**
         * User Info
         */
        'name' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
    );

    
    protected $formFields = [
        [
            'name' => 'name',
            'label' => 'name',
            'type' => 'text'
        ],
        [
            'name' => 'description',
            'label' => 'description',
            'type' => 'text'
        ],
        [
            'name' => 'url',
            'label' => 'url',
            'type' => 'text'
        ],
        [
            'name' => 'path',
            'label' => 'path',
            'type' => 'text'
        ],
        [
            'name' => 'name',
            'label' => 'name',
            'type' => 'text'
        ],
        [
            'name' => 'type',
            'label' => 'type',
            'type' => 'text'
        ],
        [
            'name' => 'filename',
            'label' => 'filename',
            'type' => 'text'
        ],
        [
            'name' => 'size',
            'label' => 'size',
            'type' => 'text'
        ],
        [
            'name' => 'last_modified',
            'label' => 'last_modified',
            'type' => 'text'
        ],
        // [
        //     'name' => 'tags',
        //     'label' => 'Tags',
        //     'type' => 'select_multiple',
        //     'relationship' => 'tags'
        // ],
    ];


    protected $indexFields = [
        'name',
        'unique_hash',
        'filesystem',
        'description',
        'url',
        'path',
        'type',
        'filename',
        'size',
        'last_modified',
        'mime',
    ];
        
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

    public function fileable()
    {
        return $this->morphTo();
    }

    public static function boot() {
        parent::boot();
        static::creating(function (File $file) {
            if (!empty($file->unique_hash)) {
                if (!$binary = Binary::where('hash', $file->unique_hash)->first()) {
                    $binary = Binary::create([
                        'hash' => $file->unique_hash
                    ]);
                };
                // dd($binary);
                if (!empty($file->extension)) $binary->extension = $file->type;
                if (!empty($file->size)) $binary->size = $file->size;
                if (!empty($file->mime)) $binary->mime = $file->mime;
                $binary->save();
            }
        });
    }
}
