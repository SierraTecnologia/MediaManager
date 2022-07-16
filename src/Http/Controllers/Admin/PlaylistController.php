<?php

namespace MediaManager\Http\Controllers\Admin;

use MediaManager\Models\Computer;
use MediaManager\Models\Playlist;
use MediaManager\Models\Video;
use MediaManager\Services\MediaService;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * @var string
     */
    public $mediaService;

    public $title = 'Playlists';
    public $description = 'Playlists';
    public $icon = 'fas fa-fw oi oi-media-play text-green';

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            $playlists = Playlist::allTeams()->orderBy('name', 'ASC')->simplePaginate(50);
        } else {
            $playlists = Playlist::orderBy('name', 'ASC')->simplePaginate(50);
        }

        return $this->populateView('admin.playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->populateView('admin.playlists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $playlist = new Playlist();
        $playlist->validateAndSetFromRequestAndSave($request);
        return redirect('/admin/playlists')->with('success', 'Playlist foi adicionado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        $videos = Video::fromTeam($playlist->team_id)->get();
        foreach ($videos as $idVideo => $video) {
            if (!$this->videoExist($video)) {
                $video->delete();
                $videos->forget($idVideo);
            }
        }

        $videos = Video::fromTeam($playlist->team_id)->orderBy('name', 'ASC')->get();
        foreach ($videos as $idVideo => $video) {
            if (!$this->videoExist($video)) {
                $video->delete();
                $videos->forget($idVideo);
            }
        }
        $videos = $videos->pluck('name', 'id');
        $videos = $videos->diff($playlist->videos->pluck('name', 'id'));
        //->reject(function ($video) {
        //     return !$this->videoExist($video);
        // });

        return $this->populateView('admin.playlists.show', compact('playlist', 'videos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        return $this->populateView('admin.playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        $playlist->validateAndSetFromRequestAndSave($request);

        return redirect('/admin/playlists')->with('success', 'Playlist foi atualizado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        $playlist->delete();

        return redirect('/admin/playlists')->with('success', 'Playlist foi deletado com sucesso');
    }

    /**
     * Include Video to Playlist
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function includeVideo(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        $playlist->updateOrderVideos();
        if (!$video = Video::fromTeam($playlist->team_id)->find($request->get('video'))) {
            return redirect('/admin/playlists/'.$id)->with('success', 'Por favor selecionar um arquivo da lista');
        }
        $playlist->videos()->save($video, ['position' => $playlist->videos()->count()]);
        return redirect('/admin/playlists/'.$id)->with('success', 'Video adicionado com sucesso');
    }

    public function removeVideo(Request $request, $id)
    {
        $playlist = $this->getPlaylist($id);

        $video = Video::fromTeam($playlist->team_id)->findOrFail($request->get('video'));
        $playlist->videos()->detach($video->id);
        $playlist->updateOrderVideos();
        return redirect('/admin/playlists/'.$id)->with('success', 'Video removido com sucesso');
    }

    public function videoUp(Request $request, $id, $position)
    {
        $playlist = $this->getPlaylist($id);

        $playlist->orderVideosUp($position);
        return redirect('/admin/playlists/'.$id)->with('success', 'Ordem atualizada com sucesso');
    }
    public function videoDown(Request $request, $id, $position)
    {
        $playlist = $this->getPlaylist($id);

        $playlist->orderVideosDown($position);
        return redirect('/admin/playlists/'.$id)->with('success', 'Ordem atualizada com sucesso');
    }

    private function getPlaylist($id): Playlist
    {
        if (!auth()->user()->isAdmin()) {
            return Playlist::findOrFail($id);
        }

        $playlist = Playlist::allTeams()->findOrFail($id);
        $this->mediaService->setDirectory($playlist->team_id);
        return $playlist;
    }
    private function videoExist(Video $video)
    {
        return $this->mediaService->videoExist($video);
    }
}
