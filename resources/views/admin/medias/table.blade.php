@if (method_exists($medias,'onEachSide'))
    {{ $medias->onEachSide(10)->links() }}
@endif
@php
$position = 0;
@endphp
<table class="table table-striped">
    <thead>
        <tr>
            <td>ID</td>
            <td>Media</td>
            <td>Ordem</td>
            <td>Ação</td>
        </tr>
    </thead>
    <tbody>
        @foreach($medias as $media)
        <tr>
            <td>{{$media->id}}</td>
            <td>{{$media->name}}</td>
            <td>
            @if ($position>0)
                <span class="text-xl text-muted"><a href="{!! route('media-manager.admin.playlists.up', ['id' => $playlist->id, 'position' => $position]) !!}"><i class="fas fa-sort-up"></i></a></span>
            @endif
            @if ($position < count($medias)-1)
                <span class="text-xl text-muted"><a href="{!! route('media-manager.admin.playlists.down', ['id' => $playlist->id, 'position' => $position]) !!}"><i class="fas fa-sort-down"></i></a></span>
            @endif
            </td>
            <td>
                <form action="{{ route('media-manager.admin.playlists.removevideo', ['id' => $playlist->id, 'video' => $media->id])}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Remover da Playlist</button>
                </form>
            </td>
        </tr>
        @php
        $position += 1;
        @endphp
        @endforeach
    </tbody>
</table>
@if (method_exists($medias,'onEachSide'))
    {{ $medias->onEachSide(10)->links() }}
@endif