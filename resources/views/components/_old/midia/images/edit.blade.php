@extends('layouts.app')

@section('content')

    <div class="row">
        <h1 class="page-header">{!! trans('features.images') !!}</h1>
    </div>

    @include('facilitador::midia.images.breadcrumbs', ['location' => ['edit']])

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="thumbnail ">
                <img src="{!! FileService::filePreview($images->location) !!}" />
            </div>
        </div>
        <div class="col-md-4">
            <h2 class="raw-margin-top-0 raw-margin-bottom-24">Blade Tags</h2>
<pre>
 &#64;image({{ $images->id }})
 &#64;image_link({{ $images->id }})
@foreach(explode(',', $images->tags) as $tag) &#64;images({{ trim($tag) }})<br>@endforeach</pre>
        </div>
    </div>

    <div class="row">
        {!! Form::model($images, ['route' => ['cms.images.update', $images->id], 'method' => 'patch', 'files' => true, 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($images, Config::get('cms.forms.images-edit')) !!}

            <div class="form-group text-right">
                <a href="{!! URL::to('admin/images') !!}" class="btn btn-secondary raw-left">{!! trans('features.cancel') !!}</a>
                {!! Form::submit(trans('features.save'), ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
