@if (!empty($playlists) && !$playlists->isEmpty())
    @if (method_exists($playlists,'onEachSide'))
        {{ $playlists->onEachSide(10)->links() }}
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <td>ID</td>
                <td>Nome</td>
                <td>Descrição</td>
                <td>Grupos</td>
                <td>Videos</td>
            </tr>
        </thead>
        <tbody>
            @foreach($playlists as $playlist)
            <tr>
                <td>{{$playlist->id}}</td>
                <td><a href="{{ route('media-manager.admin.playlists.show',$playlist->id)}}">{{$playlist->name}}</a></td>
                <td>{{$playlist->description}}</td>
                <td>{{$playlist->groups()->count()}}</td>
                <td>{{$playlist->videos()->count()}}</td>
                <?php
                /**
                <td>{{$playlist->created_at->format('d/m/Y h:i:s')}}</td>
                <td>{!!$playlist->getStatusSpan()!!}</td>
                <td>
                    <a href="{{ route('media-manager.admin.playlists.show',$playlist->id)}}" class="btn btn-primary">Mais Informações</a>
                    <!--<a href="{{ route('media-manager.admin.playlists.edit',$playlist->id)}}" class="btn btn-primary">Editar</a>
                        <form action="{{ route('media-manager.admin.playlists.destroy', $playlist->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Deletar</button>
                    </form>-->
                </td>
                $user = \Auth::user();
                if($user && $user->isRoot() && is_object($playlist->user)) {
                    echo '<td><a href="'.route('root.users.show', $playlist->user->id).'">'.$playlist->user->name.'</td>';
                }
                 */
                ?>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if (method_exists($playlists,'onEachSide'))
        {{ $playlists->onEachSide(10)->links() }}
    @endif
@endif