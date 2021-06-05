<?php

namespace MediaManager\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use MediaManager\Repositories\VideoRepository;
use Muleta\Utils\Modificators\StringModificator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VideoService
{
    // public function __construct(
    //     VideoRepository $personRepository
    // ) {
    //     $this->repo = $personRepository;
    // }

    public static function getModel()
    {
        if (class_exists(\Trainner\Models\Video::class)) {
            return \Trainner\Models\Video::class;
        }
        return \MediaManager\Models\Video::class;
    }

    /**
     * @todo Terminar de Fazer
     */
    public static function import($data)
    {   
        // $registerData = [];
        // if (isset($data['Nome Completo'])) {
        //     $registerData['name'] = $data["Nome Completo"];
        // }
        // if (isset($data['CPF'])) {
        //     $registerData['cpf'] = $data["CPF"];
        // }
        // if (isset($data['Nascimento'])) {
        //     $registerData['birthday'] = $data["Nascimento"];
        // }
        // $code = $registerData['code'] = StringModificator::cleanCodeSlug($registerData['name']);

        // if (\Telefonica\Models\Actors\Video::find($code)) {
        //     return true;
        // }
        // $person = Video::createIfNotExistAndReturn($registerData);
        // return true;
    }

    /**
     * [
                'name' => $data['name'],
                'url' => $data['path'],
                'path' => $data['relative_path'],
                'type' => $data['type'],
                'filename' => $data['filename'],
                'size' => $data['size'],
                'last_modified' => $data['last_modified'],
            ]
     */
    public static function create($data)
    {
        if (!isset($data['relative_path'])) {
            $data['relative_path'] = $data['path'];
            // unset($data['relative_path']);
        }
        if (File::exists($data['path'])) {
            $data['path'] = Storage::disk($this->filesystem)->url($item['path']);
        }
        if (!isset($data['mimetipe'])) {
            try {
                $data['type'] = FileService::getMime($data['path']); // mimetipe no MediaService
            } catch (\Throwable $th) {
                //@fixme APenasExceptionde Nao encontrado
                $data['type'] = 'file';
            }
        }

        $modelClass = self::getModel();

        if ($video = $modelClass::where('path', $data['path'])->first()) {
            return $video;
        }

        if ($video = $modelClass::where('path', $data['path'].'.mp4')->first()) {
            return $video;
        }

        if ($video = $modelClass::where('url', $data['path'])->first()) {
            return $video;
        }
        if ($video = $modelClass::where('url', $data['path'].'.mp4')->first()) {
            return $video;
        }

        return $modelClass::create($data);
    }
}
