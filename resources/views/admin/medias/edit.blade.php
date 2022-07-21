@extends('layouts.page')

@section('title', 'Videos - Editar')

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
  <div class="box-header panel-heading card-header">
    Edit Collaborator
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
      <form method="post" action="{{ route('collaborators.update', $collaborator->id) }}">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Collaborator Name:</label>
          <input type="text" class="form-control" name="name" value={{ $collaborator->name }} />
        </div>
        <div class="form-group">
          <label for="price">Collaborator Email :</label>
          <input type="text" class="form-control" name="email" value={{ $collaborator->email }} />
        </div>
        <div class="form-group">
          <label for="quantity">Collaborator Telephone:</label>
          <input type="text" class="form-control" name="telephone" value={{ $collaborator->telephone }} />
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
  </div>
</div>
@endsection