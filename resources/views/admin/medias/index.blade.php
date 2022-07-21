@extends('layouts.page')

@section('title', 'Videos')

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

  @include('media-manager::admin.collaborators.table', ['collaborators' => $collaborators])
  
<div>
@endsection