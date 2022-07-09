@extends('layouts.page')

@section('title', 'Grupos - Editar')

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
<div class="card uper">
  <div class="card-header">
    Grupos - Editar
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('admin.groups.update', $group->id) }}">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Nome do Grupo:</label>
          <input type="text" class="form-control" name="name" value="{{ $group->name }}" />
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </form>
  </div>
</div>
@endsection