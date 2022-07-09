<?php

namespace MediaManager\Http\Api;

use MediaManager\Http\Requests\ComputerRegisterRequest;
use MediaManager\Http\Resources\ComputerCollection;
use MediaManager\Http\Resources\ComputerPureResource;
use MediaManager\Http\Resources\ComputerResource;
use MediaManager\Http\Resources\VideoPureCollection;
use MediaManager\Http\Resources\VideoPureResource;
use MediaManager\Models\Computer;
// use MediaManager\Http\Requests\ComputerDeleteRequest;
// use MediaManager\Http\Requests\ComputerUserRequest;
// use MediaManager\Http\Requests\ComputerValidRequest;
// use MediaManager\Http\Requests\ComputerValidationRequest;
// use MediaManager\Http\Requests\ComputerValidateTokenRequest;
use MediaManager\Util\Filter;
use Illuminate\Support\Facades\Log;

class ComputerController extends Controller
{

    /**
     * @api {post} /groups/register register
     * @apiGroup Computers
     * @apiName register
     *
     * @apiParam {String} $token token
     *
     * @apiDescription Registra dados do dispositivo.
     *
     * @apiSuccessExample Success 200
     *     HTTP/1.1 200 OK
     *      {
     *          "success": true,
     *          "message": "Computador registrado com sucesso.",
     *          "data": {
     *              "computer_id": 1
     *          }
     *      }
     *
     *  @apiErrorExample Error 422
     *     HTTP/1.1 422 Not Found
     *     {
     *          "success": false,
     *          "message": "Você precisa informar o token."
     *     }
     *
     * @apiVersion 0.0.1
     *
     * @param ComputerRegisterRequest $request
     *
     * @return ComputerResource|\Illuminate\Http\JsonResponse
     */
    public function register(ComputerRegisterRequest $request)
    {
        $new = false;
        $computer = $this->getComputer($request);
        // $params = $request->all();
        // $computer = false;
        // if (isset($params['token']) && !$computer = Computer::where('token', md5(implode(';', $params)))->first()) {
        //     $new = true;
        //     $computer = Computer::create([
        //         'token' => $params['token']
        //     ]);
        // }
        // if (!$computer && !empty($params) && !$computer = Computer::where('token', md5(implode(';', $params)))->first()) {
        //     $new = true;
        //     $computer = Computer::create([
        //         'token' => md5(implode(';', $params))
        //     ]);
        // }

        if (!$computer) {
            return response()->json(array('success' => false, 'message' => 'Dados do dispositivo não foram enviados!'), 422);
        }

        // return (empty($videos = $computer->getVideosToPlay()) || $videos->isEmpty())?[]:VideoPureResource::collection($videos);
        // return (new ComputerPureResource($computer));
        
        // // Computer Activity @todo
        // activity()
        // ->performedOn($anEloquentModel)
        // ->causedBy($user)
        // ->withProperties(['customProperty' => 'customValue'])
        // ->log('Look, I logged something');

        return (new ComputerResource($computer))
            ->additional([
                'message' => ($new?'Dispositivo registrado com sucesso.':'Esse dispositivo já foi registrado, retornando token!'),
            ]);
    }

    public function vlc(ComputerRegisterRequest $request)
    {
        // $new = false;
        $computer = $this->getComputer($request);
        if (!$computer) {
            return response()->json(array('success' => false, 'message' => 'Dados do dispositivo não foram enviados!'), 422);
        }

        if (empty($videos = $computer->getVideosToPlay()) || $videos->isEmpty()) {
            return response("{\n}");
        }
        
        $listVideos = $videos->transform(
            function ($video, $indice) {
                // $indice = (string) $indice+1;
                return $video->getLink();
            }
        )->all();

        $jsonHtmlSimples = '{';
        $contador = 0;
        foreach ($listVideos as $video) {
            ++$contador;
            if ($contador > 1) {
                $jsonHtmlSimples .= ',';
            }
            $jsonHtmlSimples .= "\n".'"'.$contador.'": "'.$video.'"';
        }
        $jsonHtmlSimples .= "\n".'}';
        return response($jsonHtmlSimples);
    }
}
