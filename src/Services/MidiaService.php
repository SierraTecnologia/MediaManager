<?php

namespace MediaManager\Services;

use Cms;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Log;
use SplFileInfo;
use Stalker;
use MediaManager\Models\Imagen as ModelImage;

class MidiaService
{
    protected $mimeTypes;

    public function __construct()
    {
        $this->mimeTypes = include base_path('config'.DIRECTORY_SEPARATOR.'mime.php');
    }

    private function setModel(string $midiaId): void
    {
        $this->midiaId = app('Crypto')->urlDecode($midiaId);
        $this->midia = ModelImage::find($this->midiaId);
    }

    private function getCacheName(): string
    {
        return md5($this->midiaId);
    }

    /**
     * ProvmidiaIde the File as a Public Asset.
     *
     * @param string $midiaId
     *
     * @return Download
     */
    public function asFull($midiaId)
    {
        $this->setModel($midiaId);
        try {
            return Cache::remember(
                $this->getCacheName().'_asPublic',
                3600,
                function () use ($midiaId) {
                    return Response::make(
                        $this->midia->getFileContent(),
                        200,
                        [
                        'Content-Type' => $this->midia->getFileContentType(),
                        'Content-Disposition' => 'attachment; filename="'.$this->midia->getFileName().'"',
                        ]
                    );
                }
            );
        } catch (Exception $e) {
            dd('MidiaServiceimport', $e);
            return Response::make('file not found');
        }
    }

    /**
     * Provide the File as a Public Preview.
     *
     * @param string $midiaId
     *
     * @return Download
     */
    public function asPreview($midiaId)
    {
        $this->setModel($midiaId);
        try {
            return Cache::remember(
                $this->getCacheName().'_preview',
                3600,
                function () {
                    return Response::make(
                        $this->midia->getFileContent(),
                        200,
                        [
                        'Content-Type' => $this->midia->getFileContentType(),
                        'Content-Disposition' => 'attachment; filename="'.$this->midia->getFileName().'"',
                        ]
                    );
                }
            );
        } catch (Exception $e) {
            dd('MidiaServiceimport', $e);
            return Response::make('file not found');
        }
    }

    /**
     * Provide file as download.
     *
     * @param string $midiaId
     * @param string $encRealFileName
     *
     * @return Downlaod
     */
    public function asDownload($midiaId)
    {
        $this->setModel($midiaId);
        try {
            return Cache::remember(
                $this->getCacheName().'_asDownload',
                3600,
                function () use ($midiaId, $encRealFileName) {
                    $fileName = app('Crypto')->urlDecode($midiaId);
                    $realFileName = app('Crypto')->urlDecode($encRealFileName);
                    $filePath = $this->getFilePath($fileName);

                    $fileTool = new SplFileInfo($filePath);
                    $ext = $fileTool->getExtension();
                    $contentType = $this->getMimeType($ext);

                    $headers = ['Content-Type' => $contentType];
                    $fileContent = $this->getFileContent($realFileName, $contentType, $ext);

                    return Response::make(
                        $fileContent,
                        200,
                        [
                        'Content-Type' => $contentType,
                        'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
                        ]
                    );
                }
            );
        } catch (Exception $e) {
            Cms::notification('We encountered an error with that file', 'danger');

            return redirect('errors/general');
        }
    }

    /**
     * Generate an image
     *
     * @param string $ext
     *
     * @return string
     */
    public function generateImage($ext): string
    {
        if ($ext == 'File Not Found') {
            return __DIR__.'/../Assets/src/images/blank-file-not-found.jpg';
        }

        return __DIR__.'/../Assets/src/images/blank-file.jpg';
    }
}
