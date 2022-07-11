<?php

namespace MediaManager\Http\Api;

use MediaManager\Http\Requests\PlaylistRequest;
use MediaManager\Http\Resources\PlaylistResource;
use MediaManager\Models\Acesso;
use MediaManager\Models\Computer;
use MediaManager\Models\Playlist;
use MediaManager\Services\FraudAnalysi;
use MediaManager\Services\Operadora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    /**
     * @param PlaylistRequest $request
     *
     * @return PlaylistResource|string
     */
    public function show(PlaylistRequest $request)
    {
        // $params = $request->all();
        // if(!is_array($params) || !isset($params['token'])) {
        //     Log::notice('[Show Playlist] Erro! Token não informado!');
        //     return response()->json(array('success' => false, 'message' => 'Você precisa enviar o token'), 422);
        // }
        // if(!$computer = Computer::where('token', $params['token'])->first()) {
        //     Log::notice('[Show Playlist] Erro! Dispositivo não encontrado pelo token');
        //     return response()->json(array('success' => false, 'message' => 'Dispositivo não encontrado pelo token'), 422);
        // }

        $computer = $this->getComputer($request);

        $acesso = Acesso::create([
            'computer_id' => $computer->id,
            'playlist_id' => $computer->getPlaylistToRun()?$computer->getPlaylistToRun()->id:null,
        ]);
        if ($computer->group) {
            $acesso->group_id = $computer->group->id;
        }
        $acesso->save();

        if (!$playlist = $computer->getPlaylistToRun()) {
            return $this->getDefaultPlaylist();
            // @todo
            return response()->json(array('success' => false, 'message' => 'Nenhuma playlist disponibilizada'), 422);
        }


        // return $playlist->videos
        if (!is_object($playlist)) {
            Log::error('Deu algum problema que pois era pra retornar uma playlist');
            Log::error($playlist);
            Log::error($computer->getPlaylistToRun());
            return $this->getDefaultPlaylist();
        }
        $playlist->load('videos');
        return (new PlaylistResource($playlist));
    }
}
