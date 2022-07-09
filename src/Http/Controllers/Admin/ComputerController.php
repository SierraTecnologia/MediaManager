<?php

namespace MediaManager\Http\Controllers\Admin;

use MediaManager\Models\Computer;
use MediaManager\Models\Group;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public static $title = 'Dispositivos';
    public static $description = 'Dispositivos';
    public static $icon = 'fas fa-fw fa-desktop text-green';
    public $subTitle = 'Dispositivos';

    public function title()
    {
        return $this->subTitle;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $computers = Computer::isBlock()->where('is_active', true)->orderBy('name', 'ASC')->simplePaginate(50);

        return $this->populateView('admin.computers.index', compact('computers'));
    }
    public function pendentes()
    {
        self::$icon = 'fas fa-fw fa-desktop text-red';
        $this->subTitle = 'Dispositivos Pendentes de Ativação';
        $computers = Computer::isBlock()->where('is_active', false)->orderBy('name', 'ASC')->simplePaginate(50);
        $groups = Group::orderBy('name', 'ASC')->get()->pluck('name');
        return $this->populateView('admin.computers.pendentes', compact('computers', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->populateView('admin.computers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $computer = new Computer();
        $computer->validateAndSetFromRequestAndSave($request);
        return redirect('/admin/computers')->with('success', 'Dispositivo foi adicionado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $computer = Computer::findOrFail($id);
        return $this->populateView('admin.computers.show', compact('computer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $computer = Computer::findOrFail($id);

        return $this->populateView('admin.computers.edit', compact('computer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request, $id)
    {
        $computer = Computer::findOrFail($id);
        $computer->is_active = true;
        $computer->validateAndSetFromRequestAndSave($request);

        return redirect(route('admin.computers.edit', $computer->id))->with('success', 'Dispositivo ativado com sucesso');
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
        $computer = Computer::findOrFail($id);
        $computer->validateAndSetFromRequestAndSave($request);

        return redirect('/admin/computers')->with('success', 'Dispositivo foi atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $computer = Computer::findOrFail($id);
        $computer->is_active = false;
        $computer->blocked_at = now();
        $computer->save();
        // $computer->delete();

        return redirect('/admin/computers')->with('success', 'Dispositivo foi deletado com sucesso');
    }
}
