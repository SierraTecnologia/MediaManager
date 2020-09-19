@extends('layouts.app')

@section('pageTitle') Files @stop

@section('content')

    <div class="col-md-12 mt-2">
        @include('facilitador::midia.files.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12 raw-margin-bottom-48 raw-margin-top-48 text-center">
        <a class="btn btn-secondary" href="{!! FileService::fileAsDownload($files->name, $files->location) !!}"><span class="fa fa-download"></span> Download: {!! $files->name !!}</a>
    </div>

    <div class="col-md-12">
        {!! Form::model($files, ['route' => ['admin.files.update', $files->id], 'files' => true, 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::setColumns(2)->fromObject($files, \Illuminate\Support\Facades\Config::get('cms.forms.file-edit')) !!}

            <div class="form-group text-right">
                <a href="{!! url('admin/'.'files') !!}" class="btn btn-secondary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
