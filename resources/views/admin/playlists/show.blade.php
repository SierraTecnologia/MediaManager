@extends('layouts.page')

@section('title', 'Playlist - '.$playlist->name)

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
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('media-manager.admin.playlists.index') }}"> Voltar</a>
                <a class="btn btn-primary" href="{{ route('media-manager.admin.playlists.edit', $playlist->id) }}"> Editar</a>
                @if ($playlist->groups->count()==0)
                    <form action="{{ route('media-manager.admin.playlists.destroy', $playlist->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Deletar</button>
                    </form>
                @endif
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    Videos da Playlist
                </h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <?= Former::open('/admin/playlists/'.$playlist->id.'/addvideo') ?>
                    <?= Former::select('video')->options($videos)->label(false) ?>
                    <button class="btn btn-primary" type="submit">Adicionar Video</button>
                <?= Former::close() ?>
                @include('media-manager:admin.medias.table', [
                    'medias' => $playlist->medias()->paginate(),
                    'playlist' => $playlist
                ])</h3>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>


@endsection