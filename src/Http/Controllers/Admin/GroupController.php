<?php

namespace MediaManager\Http\Controllers\Admin;

use MediaManager\Models\Computer;
use MediaManager\Models\Group;
use MediaManager\Models\Playlist;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public static $title = 'Grupos';
    public static $description = 'Grupos';
    public static $icon = 'fas fa-fw fa-users text-yellow';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::orderBy('name', 'ASC')->simplePaginate(50);

        return $this->populateView('admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->populateView('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new Group();
        $group->validateAndSetFromRequestAndSave($request);
        return redirect('/admin/groups')->with('success', 'Grupo foi adicionado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);

        $playlists = Playlist::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $playlists[0] = 'Sem Playlist';

        $computers = Computer::isBlock()->where('is_active', true)->where('group_id', null)->orderBy('name', 'ASC')->get()->pluck('name', 'id');
        // $computers = $computers->diff($group->computers->pluck('name', 'id'));
        return $this->populateView('admin.groups.show', compact('group', 'playlists', 'computers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Group::findOrFail($id);

        return $this->populateView('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->validateAndSetFromRequestAndSave($request);

        return redirect('/admin/groups')->with('success', 'Grupo foi atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return redirect('/admin/groups')->with('success', 'Grupo foi deletado com sucesso');
    }

    public function changeplaylist(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        if ($request->get('playlist')==0) {
            $group->playlist_id = null;
        } else {
            $group->playlist_id = Playlist::findOrFail($request->get('playlist'))->id;
        }
        $group->save();
        return redirect('/admin/groups/'.$id)->with(
            'success',
            'Playlist Alterada com sucesso'
            // 'Grupo '.$group->name.' vinculado a playlist '..' com sucesso'
        );
    }
    /**
     * Include Video to Playlist
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adddispositivo(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $computer = Computer::findOrFail($request->get('computer'));
        $computer->group_id = $group->id;
        $computer->save();
        return redirect('/admin/groups/'.$id)->with('success', 'Dispositivo adicionado com sucesso');
    }
}
