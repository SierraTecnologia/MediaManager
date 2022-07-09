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
  <a class="btn btn-primary" href="{{ route('admin.playlists.create') }}"> Criar nova Playlist</a>

    @include('admin.playlists.table', ['playlists' => $playlists])
    {{-- @include('admin.playlists.table-ajax') --}}

  <div>
@endsection