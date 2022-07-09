<?php

namespace MediaManager\Http\Controllers\Admin;

use MediaManager\Models\Acesso;
use MediaManager\Models\Computer;
use MediaManager\Models\Group;
use MediaManager\Models\Playlist;
use MediaManager\Models\Role;
use MediaManager\Models\User;
use MediaManager\Models\Video;
use MediaManager\Services\MediaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public static $title = 'Dashboard';
    public static $description = 'Dashboard';
    public static $icon = 'fas fa-fw fa-tachometer-alt';

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $playlistCounts = Playlist::orderBy('id', 'DESC')->count();
        $groups = Group::orderBy('id', 'DESC')->count();
        // $videos = Video::orderBy('id', 'DESC')->count();
        $computers = Computer::isBlock()->where('is_active', true)->orderBy('id', 'DESC')->count();
        $processingPlaylists = 0;

        $videos = Video::all();
        foreach ($videos as $idVideo => $video) {
            if (!$this->videoExist($video)) {
                $video->delete();
                $videos->forget($idVideo);
            }
        }
        $videos = $videos->count();
        
        $lastsAcessos = Acesso::orderBy('created_at', 'DESC')->activityOlderThan(10)->get();
        // ->groupBy('acessos.computer_id')
        return $this->populateView(
            'admin.dashboard',
            compact(
                'playlistCounts',
                'videos',
                'groups',
                'computers',
                'processingPlaylists',
                'lastsAcessos'
            )
        );
    }
    public function videoExist(Video $video)
    {
        return $this->mediaService->videoExist($video);
    }
}
