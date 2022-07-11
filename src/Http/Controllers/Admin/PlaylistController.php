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
        $playlists = Playlist::orderBy('name', 'ASC')->simplePaginate(50);

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
        $playlist = Playlist::findOrFail($id);

        $videos = Video::all();
        foreach ($videos as $idVideo => $video) {
            if (!$this->videoExist($video)) {
                $video->delete();
                $videos->forget($idVideo);
            }
        }

        $videos = Video::orderBy('name', 'ASC')->get();
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
        $playlist = Playlist::findOrFail($id);

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
        $playlist = Playlist::findOrFail($id);
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
        $playlist = Playlist::findOrFail($id);
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
        $playlist = Playlist::findOrFail($id);
        $playlist->updateOrderVideos();
        if (!$video = Video::find($request->get('video'))) {
            return redirect('/admin/playlists/'.$id)->with('success', 'Por favor selecionar um arquivo da lista');
        }
        $playlist->videos()->save($video, ['position' => $playlist->videos()->count()]);
        return redirect('/admin/playlists/'.$id)->with('success', 'Video adicionado com sucesso');
    }

    public function removeVideo(Request $request, $id)
    {
        $playlist = Playlist::findOrFail($id);
        $video = Video::findOrFail($request->get('video'));
        $playlist->videos()->detach($video->id);
        $playlist->updateOrderVideos();
        return redirect('/admin/playlists/'.$id)->with('success', 'Video removido com sucesso');
    }
    public function videoExist(Video $video)
    {
        return $this->mediaService->videoExist($video);
    }

    public function videoUp(Request $request, $id, $position)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->orderVideosUp($position);
        return redirect('/admin/playlists/'.$id)->with('success', 'Ordem atualizada com sucesso');
    }
    public function videoDown(Request $request, $id, $position)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->orderVideosDown($position);
        return redirect('/admin/playlists/'.$id)->with('success', 'Ordem atualizada com sucesso');
    }
}
