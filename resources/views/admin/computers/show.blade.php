@extends('layouts.page')

@section('title', 'Visualizar - Dispostivo')

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

        <div class="col-lg-1 margin-tb">

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('admin.media-manager.computers.index') }}"> Voltar</a>

            </div>

        </div>
        <div class="col-lg-1 margin-tb">
                <a class="btn btn-primary" href="{{ route('admin.media-manager.computers.edit', $computer->id) }}"> Editar</a>
        </div>
        <div class="col-lg-10 margin-tb">
            <form action="{{ route('admin.media-manager.computers.destroy', $computer->id)}}" method="post">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Deletar</button>
            </form>
        </div>
    </div>


    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">


        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>
                    Id</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $computer->id }}
                </blockquote>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>
                    Nome</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $computer->name }}
                </blockquote>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>
                    Criado em</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $computer->created_at }}
                </blockquote>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>

@endsection