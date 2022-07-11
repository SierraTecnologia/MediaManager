@extends('layouts.page')

@section('title', 'Dispositivos Ativos')

@section('css')
  <style>
    .uper {
      margin-top: 40px;
    }
  </style>
@stop

@section('js')

@stop

@section('content')
<div class="uper">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif

  @include('media-manager:admin.computers.table', ['computers' => $computers])
  
<div>
@endsection