@extends('layouts.page')

@section('title', 'Grupo - '.$group->name)

@section('css')

@stop

@section('js')

@stop

@section('content')

    @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}  
      </div><br />
    @endif
    
    <div class="row">

        <div class="col-lg-1 margin-tb">

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('admin.media-manager.groups.index') }}"> Voltar</a>

            </div>

        </div>
        <div class="col-lg-1 margin-tb">
                <a class="btn btn-primary" href="{{ route('admin.media-manager.groups.edit', $group->id) }}"> Editar</a>
        </div>
        <div class="col-lg-1 margin-tb">
                @if ($group->computers->count()==0)
                    <form action="{{ route('admin.media-manager.groups.destroy', $group->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Deletar</button>
                    </form>
                @endif
        </div>
        <div class="col-lg-9 margin-tb">
            <?= Former::open('/admin/groups/'.$group->id.'/changeplaylist') ?>
            <div class="row">
                <div class="col-lg-4 margin-tb text-right">
                    <label for="playlist" class="control-label col-lg-2 col-sm-4">Playlist</label>
                </div>
                <div class="col-lg-4 margin-tb">
                    <?= Former::select('playlist')->options($playlists, $group->playlist?$group->playlist->id:0)->label(false) ?>
                </div>
                <div class="col-lg-4 margin-tb">
                    <button class="btn btn-primary" type="submit">Salvar</button>
                </div>
            </div>
        <?= Former::close() ?>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    Dispositivos nesse Grupo
                </h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                @if (!empty($computers) && !$computers->isEmpty())
                    <?= Former::open('/admin/groups/'.$group->id.'/adddispositivo') ?>
                        <?= Former::select('computer')->options($computers)->label(false) ?>
                        <button class="btn btn-primary" type="submit">Adicionar Dispositivo</button>
                    <?= Former::close() ?>
                @endif
                @include('media-manager::admin.computers.table', [
                    'contexto' => \MediaManager\Models\Group::class,
                    'contextoId' => $group->id,
                    'computers' => $group->computers()->paginate()]
                )</h3>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>

@endsection