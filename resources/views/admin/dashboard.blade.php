@extends('layouts.page')

@section('title', 'Endotera')

@section('content')
    @include('admin.numbers', [
        'playlists' => $playlistCounts,
        'computers' => $computers,
        'groups' => $groups,
        'processingPlaylists' => $processingPlaylists,
    ])

<div class="row">
    <div class="col-md-12">
        <div class="box box-info card card-info">
            <div class="box-header card-header with-border">
            <h3 class="box-title card-title">Log de Acessos</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body table-responsive p-0">
                    @include('admin.acessos.table', ['acessos' => $lastsAcessos])
            </div>
            <!-- /.box-body card-body -->
            <div class="box-footer card-footer clearfix">
            {{-- <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Novo Dispositivo</a> --}}
            </div>
            <!-- /.box-footer card-footer -->
        </div>
    </div>

@stop
</div>