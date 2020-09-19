<div class="row">
    <ol class="breadcrumb">
        <li><a href="{!! url('admin/files') !!}">{!! trans('features.files') !!}</a></li>

        @foreach($location as $local)

            <li>{!! ucfirst($local) !!}</li>

        @endforeach
        <li class="active"></li>
    </ol>
</div>