@extends('layouts.page')

@section('title', 'Dispositivos Pendentes de Ativação')

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

  @include('media-manager:admin.computers.table-pendentes', ['computers' => $computers])
  
<div>
@endsection