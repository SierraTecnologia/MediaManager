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
        'actors',
        'language',
        'url',
        'time',
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
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('sitec.core.models.user', \App\Models\User::class), 'videoable');
    }

    /**
     * Get all of the persons that are assigned this video.
     */
    public function persons()
    {
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('sitec.core.models.person', \Telefonica\Models\Actors\Person::class), 'videoable');
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
