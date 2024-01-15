<?php

namespace MediaManager\Models;

use Crypto;
use Illuminate\Database\Eloquent\Model as Base;
use Muleta\Traits\UsesStringId;

class Binary extends Base
{
    use UsesStringId;

    protected $primaryKey = 'hash';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'extension',
        'size',
        'mime',
        'hash',
    ];

    protected $mappingProperties = array(
        /**
         * User Info
         */
        'extension' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
    );

    
    protected $formFields = [
        [
            'name' => 'extension',
            'label' => 'extension',
            'type' => 'text'
        ],
        [
            'name' => 'size',
            'label' => 'size',
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
        'type',
        'extension',
        'size',
        'mime',
        'hash',
    ];
        

    // public function binaryable()
    // {
    //     return $this->morphTo();
    // }
}
