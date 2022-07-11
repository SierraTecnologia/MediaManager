@extends('layouts.page')

@section('title', 'Visualizar Video')

@section('css')

@stop

@section('js')

@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2> Visualizar Video</h2>

            </div>

            <div class="pull-right">

                <a class="btn btn-primary" href="{{ route('media-manager.admin.playlists.index') }}"> Voltar</a>

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>
                    
                    Video Playlists
                </h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                @include('media-manager:admin.playlists.table', ['playlists' => $video->orders()->paginate()])</h3>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>
                    
                    Grupos
                </h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                @include('media-manager:admin.groups.table', ['groups' => $video->groups()->paginate()])</h3>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>

    <?php /*
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-solid card card-solid">
            <div class="box-header card-header with-border">
                <h3 class="box-title card-title">
                    <i class="fa fa-text-width"></i>

                    Grupos
                </h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                @include('media-manager:admin.groups.table', ['groups' => $video->groups()->paginate()])</h3>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>
    */ ?>

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
                    {{ $video->id }}
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
                    {{ $video->name }}
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
                    created_at</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $video->created_at }}
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
                    updated_at</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $video->updated_at }}
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
                    score_points</h3>
            </div>
            <!-- /.box-header card-header -->
            <div class="box-body card-body">
                <blockquote>
                    {{ $video->score_points }}
                </blockquote>
            </div>
            <!-- /.box-body card-body -->
        </div>
        </div>
    </div>

@endsection