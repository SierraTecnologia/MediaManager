@extends('layouts.page')

@section('title', 'Playlists')

@section('css')

@stop

@section('js')

@stop

@section('content')
  <style>
    .uper {
      margin-bottom: 40px;
    }
  </style>
  <div class="uper">
    @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}  
      </div><br />
    @endif
  <a class="btn btn-primary" href="{{ route('media-manager.admin.playlists.create') }}"> Criar nova Playlist</a>

    @include('media-manager:admin.playlists.table', ['playlists' => $playlists])
    {{-- @include('media-manager:admin.playlists.table-ajax') --}}

  <div>
@endsection