@extends('layouts.app')

@section('content')

    <div class="row">
        <h1 class="page-header">{!! trans('features.images') !!}</h1>
    </div>

    @include('facilitador::midia.images.breadcrumbs', ['location' => ['create']])

    <div class="row">
        {!! Form::open(['url' => 'admin/images/upload', 'files' => true, 'class' => 'dropzone', 'id' => 'fileDropzone']); !!}
        {!! Form::close() !!}

        {!! Form::open(['route' => 'admin.images.store', 'files' => true, 'id' => 'fileDetailsForm', 'class' => 'add']) !!}

            {!! FormMaker::fromTable('files', Config::get('cms.forms.images')) !!}

            <div class="form-group text-right">
                <a href="{!! URL::to('admin/images') !!}" class="btn btn-secondary raw-left">{!! trans('features.cancel') !!}</a>
                {!! Form::submit(trans('features.save'), ['class' => 'btn btn-primary', 'id' => 'saveImagesBtn']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
