<?php

namespace MediaManager\Models;

use MediaManager\Models\Model as Base;

class Video extends Base

{


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'unique_hash',
        'url',
        'path',
        'mime',
        'filename',
        'size',
        'last_modified',
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

    public static function boot() {
        parent::boot();
        static::creating(function (Video $video) {
            // dd($video); //@todo
            $video->name = \str_replace('.mp4', '', $video->name); // Porque ? @todo

        });
    }
        
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

    /**
     * Get all of the playlists for the post.
     */
    public function playlists() {
        return $this->morphedByMany(\MediaManager\Models\Playlist::class, 'videoable')
            ->withTimestamps();
    }
    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'name',
    //     'actors',
    //     'language',
    //     'url',
    //     'time',
    // ];

    // public function thumbnails()
    // {
    //     return $this->morphMany(Thumbnail::class, 'thumbnailable')
    //     ->orderBy('width')
    //     ->orderBy('height');
    // }

    public function links()
    {
        return $this->sitios();
    }

    public function sitios()
    {
        return $this->morphToMany('Telefonica\Models\Digital\Sitio', 'sitioable');
    }

    /**
     * Get all of the users that are assigned this video.
     */
    public function users()
    {
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('sitec.core.models.user', \MediaManager\Models\User::class), 'videoable');
    }

    /**
     * Get all of the persons that are assigned this video.
     */
    public function persons()
    {
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('sitec.core.models.person', \Telefonica\Models\Actors\Person::class), 'videoable');
    }
        


    public function videoable()
    {
        return $this->morphTo();
    }
    // // /**
    // //  * Get all of the owning videoable models.
    // //  */
    // // @todo Verificar Depois
    // public function videoable()
    // {
    //     return $this->morphTo(); //, 'videoable_type', 'videoable_code'
    // }
}
