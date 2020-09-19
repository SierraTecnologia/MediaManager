@extends('layouts.app')

@section('content')

    <div class="row">
        <h1 class="page-header">{!! trans('features.images') !!}</h1>
    </div>

    @include('media-manager::admin.images.breadcrumbs', ['location' => ['edit']])

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="thumbnail ">
                <img src="{!! FileService::filePreview($item->location) !!}" />
            </div>
        </div>
        <div class="col-md-4">
            <h2 class="raw-margin-top-0 raw-margin-bottom-24">Blade Tags</h2>
<pre>
 &#64;image({{ $item->id }})
 &#64;image_link({{ $item->id }})
@foreach(explode(',', $item->tags) as $tag) &#64;item({{ trim($tag) }})<br>@endforeach</pre>
        </div>
    </div>

    <div class="row">
        {!! Form::model($item, ['route' => ['siravel.images.update', $item->id], 'method' => 'patch', 'files' => true, 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($item, Config::get('siravel.forms.images-edit')) !!}

            <div class="form-group text-right">
                <a href="{!! URL::to('admin/images') !!}" class="btn btn-default raw-left">{!! trans('features.cancel') !!}</a>
                {!! Form::submit(trans('features.save'), ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
