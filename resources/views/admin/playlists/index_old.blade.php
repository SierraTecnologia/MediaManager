
  <style>
    .uper {
      margin-top: 40px;
    }
  </style>
  <div class="uper">
    @if(session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}  
      </div><br />
    @endif

    {{ Form::open(array('method' => 'get', 'route' => array('admin.playlists.index'))) }}
    <div class="input-group input-group-sm">
        {!! Form::text('query', null, ['class' => 'form-control']) !!}
        <span class="input-group-btn">
            {{ Form::button('Search!', array('class' => 'btn btn-info btn-flat', 'type' => 'submit')) }}
        </span>
    </div>
    {{ Form::close() }}

    @include('admin.playlists.table', ['playlists' => $playlists])
    {{-- @include('admin.playlists.table-ajax') --}}

  <div>