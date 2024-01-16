@extends('layouts.page')

@section('title', 'Grupos')

@section('css')

@stop

@section('js')

@stop

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif
  <a class="btn btn-primary" href="{{ route('admin.media-manager.groups.create') }}"> Criar novo Grupo</a>

  @include('media-manager::admin.groups.table', ['groups' => $groups])
  
<div>
@endsection