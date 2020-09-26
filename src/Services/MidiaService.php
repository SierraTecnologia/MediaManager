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
use Stalker\Facades\CryptoServiceFacade;
use Stalker\Models\Imagen as ModelImage;
use Stalker\Models\Media;

class MidiaService
{
    protected $mimeTypes;

    private function setModel(string $midiaId): void
    {
        $this->midiaId = CryptoServiceFacade::url_decode($midiaId);
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
            dd($e);
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
            dd($e);
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
                    $fileName = CryptoServiceFacade::url_decode($midiaId);
                    $realFileName = CryptoServiceFacade::url_decode($encRealFileName);
                    $filePath = $this->getFilePath($fileName);

                    $fileTool = new SplFileInfo($filePath);
                    $ext = $fileTool->getExtension();
                    $contentType = $this->getMimeType($ext);

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
}
