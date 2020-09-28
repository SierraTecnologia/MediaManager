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

    public function links()
    {
        return $this->sitios();
    }

    public function sitios(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany('Telefonica\Models\Digital\Sitio', 'sitioable');
    }

    /**
     * Get all of the users that are assigned this video.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('application.directorys.models.users', \App\Models\User::class), 'videoable');
    }

    /**
     * Get all of the persons that are assigned this video.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function persons(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphedByMany(\Illuminate\Support\Facades\Config::get('application.directorys.models.persons', \Telefonica\Models\Actors\Person::class), 'videoable');
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
