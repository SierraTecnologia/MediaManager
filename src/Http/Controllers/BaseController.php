<?php

namespace MediaManager\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use League\Flysystem\Util;
use Illuminate\Http\Request;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 * @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class BaseController
 */
class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }


    public function assets(Request $request)
    {
        try {
            $path = dirname(__DIR__, 3).'/publishes/assets/'.Util::normalizeRelativePath(urldecode($request->path));
        } catch (\LogicException $e) {
            abort(404);
        }

        if (File::exists($path)) {
            $mime = '';
            if (Str::endsWith($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (Str::endsWith($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        return response('', 404);
    }

}
