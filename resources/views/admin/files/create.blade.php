@extends('layouts.app')

@section('pageTitle') Files @stop

@section('content')

    <div class="col-md-12 mt-2">
        @include('media-manager::admin.files.breadcrumbs', ['location' => ['create']])
    </div>

    <div class="col-md-12">
        {!! Form::open(['url' => url('admin/'.'files/upload'), 'files' => true, 'class' => 'dropzone', 'id' => 'fileDropzone']); !!}
        {!! Form::close() !!}
    </div>

    <div class="col-md-12">
        {!! Form::open(['route' => 'admin.media-manager.files.store', 'files' => true, 'id' => 'fileDetailsForm', 'class' => 'add']); !!}

            {!! FormMaker::setColumns(2)->fromTable('files', config('siravel.forms.files')) !!}

            <div class="form-group text-right">
                <a href="{!! url('admin/'.'files') !!}" class="btn btn-secondary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'saveFilesBtn']) !!}
            </div>

        {!! Form::close() !!}
    </div>
@endsection
