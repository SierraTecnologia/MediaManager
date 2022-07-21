<?php

namespace MediaMAnager\Models;

use Carbon\Carbon;
use Exception;
use Trainner\Traits\UsedByTeams;

class Group extends Model
{

    use UsedByTeams;
    public const RULES = [
        'name'=>'required',
        // 'group_qty' => 'required|integer'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'playlist_id',
    ];

    protected $mappingProperties = array(

        'name' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
        'description' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
        'playlist_id' => [
            'type' => 'id',
            "analyzer" => "standard",
        ],

    );
    
    public function playlist()
    {
        return $this->belongsTo('MediaManager\Models\Playlist', 'playlist_id', 'id')->fromTeam($this->team_id);
    }
    /**
     * Get the tokens record associated with the user.
     */
    public function computers()
    {
        return $this->hasMany('Trainner\Models\Computer', 'group_id', 'id')->fromTeam($this->team_id);
    }


}