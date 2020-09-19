@extends('layouts.app')

@section('content')
    <div class="row">
        <h1 class="page-header">{!! trans('features.files') !!}</h1>
    </div>

    @include('facilitador::midia.files.breadcrumbs', ['location' => ['create']])

    <div class="row">
        {!! Form::open(['url' => 'admin/files/upload', 'files' => true, 'class' => 'dropzone', 'id' => 'fileDropzone']); !!}
        {!! Form::close() !!}
    </div>

    <div class="row">
        {!! Form::open(['route' => 'admin.files.store', 'files' => true, 'id' => 'fileDetailsForm', 'class' => 'add']); !!}

            {!! FormMaker::fromTable('files', Config::get('cms.forms.files')) !!}

            <div class="form-group text-right">
                <a href="{!! URL::to('admin/files') !!}" class="btn btn-default raw-left">{!! trans('features.cancel') !!}</a>
                {!! Form::submit(trans('features.save'), ['class' => 'btn btn-primary', 'id' => 'saveFilesBtn']) !!}
            </div>

        {!! Form::close() !!}
    </div>
@endsection
