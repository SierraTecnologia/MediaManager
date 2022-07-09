<?php

namespace MediaManager\Http\Api\Admin;

use MediaManager\Models\Computer;
use MediaManager\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{


    /**
     * Include Video to Playlist
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adddispositivo(Request $request, $group, $computer)
    {
        $group = Group::findOrFail($group);
        $computer = Computer::findOrFail($computer);
        $computer->group_id = $group->id;
        $computer->save();
        return response()->json(
            'Dispositivo adicionado com sucesso'
        );
    }
    /**
     * Include dispositivo to group
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rmdispositivo(Request $request, $group, $computer)
    {
        // $group = Group::findOrFail($group);
        $computer = Computer::findOrFail($computer);
        $computer->group_id = null;
        $computer->save();
        return response()->json(
            'Dispositivo removido com sucesso'
        );
    }

}