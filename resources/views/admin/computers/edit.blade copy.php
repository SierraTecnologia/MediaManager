@extends('layouts.page')

@section('title', 'Dispositivos - Editar')

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
<div class="card uper">
  <div class="box-header panel-heading card-header">
    Editar Dispositivo
  </div>
  <div class="box-body panel-body card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('admin.media-manager.computers.update', $computer->id) }}">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Nome:</label>
          <input type="text" class="form-control" name="name" value={{ Template::clean($computer->name) }} />
        </div>
        <div class="form-group">
          <label for="name">Descrição:</label>
          <input type="text" class="form-control" name="description" value={{ Template::clean($computer->description) }} />
        </div>
        <div class="form-group">
          <label for="name">Local:</label>
          <input type="text" class="form-control" name="local" value={{ Template::clean($computer->local) }} />
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </form>
  </div>
</div>
@endsection