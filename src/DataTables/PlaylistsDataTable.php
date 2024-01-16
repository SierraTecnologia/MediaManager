<?php

namespace MediaManager\DataTables;

use MediaManager\DataTables\UsersDataTable;
use MediaManager\Http\Requests;
use MediaManager\Models\Playlist;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class PlaylistsDataTable extends DataTable
{
    public function query()
    {
        $query = Playlist::with('user', 'operadora', 'collaborator', 'group', 'money')->select('orders.*');

        return $this->applyScopes($query);

        //     return Datatables::of($query)->addColumn('action', function ($playlist) {
        //         return '<a href="'.route('admin.media-manager.playlists.show',$playlist->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye"></i> Show</a>';
        //     })->setRowId('id')->editColumn('created_at', function ($user) {
        //         return $user->updated_at->format('h:m:s d/m/Y');
        //     })
        //     ->setRowClass(function ($playlist) {
        //         return $playlist->status == Playlist::$STATUS_APPROVED ? 'alert-success' : 'alert-warning';
        //     })
        //     ->setRowData([
        //         'id' => 'test',
        //     ])
        //     ->setRowAttr([
        //         'color' => 'red',
        //     ])->make(true);

        //     return $this->dataTable->eloquent($query)->make(true);
    }

    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->make(true);
    }

    public function html()
    {
        return $this->builder()
            ->columns([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ])
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset', 'reload'],
            ]);
    }
}