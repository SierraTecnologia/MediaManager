@extends('layouts.app')

@section('content')

    <div class="row">
        <h1 class="page-header">{!! trans('features.files') !!}</h1>
    </div>

    @include('facilitador::midia.files.breadcrumbs', ['location' => ['edit']])

    <div class="row raw-margin-bottom-48 raw-margin-top-48 text-center">
        <a class="btn btn-secondary" href="{!! FileService::fileAsDownload($files->name, $files->location) !!}"><span class="fa fa-download"></span> {!! trans('features.download') !!}: {!! $files->name !!}</a>
    </div>

    <div class="row">
        {!! Form::model($files, ['route' => ['cms.files.update', $files->id], 'files' => true, 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($files, Config::get('cms.forms.file-edit')) !!}

            <div class="form-group text-right">
                <a href="{!! URL::to('admin/files') !!}" class="btn btn-secondary raw-left">{!! trans('features.cancel') !!}</a>
                {!! Form::submit(trans('features.save'), ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection

@section('javascript')

    @parent
    <script type="text/javascript" src="{!! CmsService::asset('js/bootstrap-tagsinput.min.js', 'application/javascript') !!}"></script>
    <script type="text/javascript" src="{!! CmsService::asset('packages/dropzone/dropzone.js', 'application/javascript') !!}"></script>
    <script type="text/javascript" src="{!! CmsService::asset('js/files-module.js', 'application/javascript') !!}"></script>
    <script type="text/javascript" src="{!! CmsService::asset('js/dropzone-custom.js', 'application/javascript') !!}"></script>

@stop

