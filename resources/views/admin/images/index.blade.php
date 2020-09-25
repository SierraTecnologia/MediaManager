@extends('layouts.app')

@section('content')

    <div class="modal fade" id="deleteModal" tabindex="-3" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">Delete Images</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this image?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a id="deleteBtn" type="button" class="btn btn-warning" href="#">{!! trans('features.confirmDelete') !!}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <a class="btn btn-primary float-right" href="{!! route('admin.images.create') !!}">{!! trans('features.addNew') !!}</a>
        <div class="raw-m-hide raw-m-hide float-right">
            {!! Form::open(['url' => 'admin/images/search']) !!}
            <input class="form-control header-input float-right raw-margin-right-24" name="term" placeholder="Search">
            {!! Form::close() !!}
        </div>
        <h1 class="page-header">{!! trans('features.images') !!}</h1>
    </div>

    <div class="row">
        @if (isset($term))
        <div class="well text-center">Searched for "{!! $term !!}".</div>
        @endif

        @if ($results->isEmpty())
            <div class="well text-center">No images found.</div>
        @else
            <div class="row">
                @foreach($results as $image)
                    <div class="col-md-3 panel raw-margin-top-24">
                        <div class="thumbnail">
                            <a href="{!! route('admin.images.edit', [$image->id]) !!}">
                                <div class="img" style="background-image: url('{!! $image->url !!}')"></div>
                            </a>
                        </div>
                        <div class="well pull-down overflow-hidden">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    @if ($image->is_published)
                                        <span clas="float-left"><span class="float-left fa fa-check"></span> Published</span>
                                    @else
                                        <span clas="float-left"><span class="float-left fa fa-close"></span> Published</span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <form method="post" action="{!! url('admin/images/'.$image->id) !!}">
                                        {!! csrf_field() !!}
                                        {!! method_field('DELETE') !!}
                                        <button class="delete-btn btn btn-xs btn-danger float-right" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                    <a class="btn btn-xs btn-secondary float-right raw-margin-right-8" href="{!! route('admin.images.edit', [$image->id]) !!}"><i class="fa fa-pencil"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="text-center">
        {!! $pagination !!}
    </div>

@endsection
