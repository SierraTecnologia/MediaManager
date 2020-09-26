<?php

namespace MediaManager\Entities\Uploads;

use App\Models\User;
use SiUtils\Exceptions\HttpFetchException;
use SiUtils\Exceptions\ImageUploadException;
use DB;
use Exception;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Filesystem\Factory as FileSystem;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService extends UploadService
{

    protected ImageManager $imageTool;
    protected Cache $cache;
    protected $storageUrl;
    protected Image $image;
    protected HttpFetcher $http;

    /**
     * Get the storage that will be used for storing images.
     *
     * @param  string $type
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getStorage($type = '')
    {
        $storageType = \Illuminate\Support\Facades\Config::get('filesystems.default');

        // Override default location if set to local public to ensure not visible.
        if ($type === 'system' && $storageType === 'local_secure') {
            $storageType = 'local';
        }

        return $this->fileSystem->disk($storageType);
    }

    /**
     * Saves a new image from an upload.
     *
     * @param  UploadedFile $uploadedFile
     * @param  string       $type
     * @param  int          $uploadedTo
     * @return mixed
     * @throws ImageUploadException
     */
    public function saveNewFromUpload(UploadedFile $uploadedFile, $type, $uploadedTo = 0)
    {
        $imageName = $uploadedFile->getClientOriginalName();
        $imageData = file_get_contents($uploadedFile->getRealPath());
        return $this->saveNew($imageName, $imageData, $type, $uploadedTo);
    }

    /**
     * Save a new image from a uri-encoded base64 string of data.
     *
     * @param  string $base64Uri
     * @param  string $name
     * @param  string $type
     * @param  int    $uploadedTo
     * @return Image
     * @throws ImageUploadException
     */
    public function saveNewFromBase64Uri(string $base64Uri, string $name, string $type, $uploadedTo = 0)
    {
        $splitData = explode(';base64,', $base64Uri);
        if (count($splitData) < 2) {
            throw new ImageUploadException("Invalid base64 image data provided");
        }
        $data = base64_decode($splitData[1]);
        return $this->saveNew($name, $data, $type, $uploadedTo);
    }

    /**
     * Gets an image from url and saves it to the database.
     *
     * @param  $url
     * @param  string      $type
     * @param  bool|string $imageName
     * @return mixed
     * @throws \Exception
     */
    private function saveNewFromUrl(string $url, $type, $imageName = false)
    {
        $imageName = $imageName ? $imageName : basename($url);
        try {
            $imageData = $this->http->fetch($url);
        } catch (HttpFetchException $exception) {
            throw new \Exception(trans('errors.cannot_get_image_from_url', ['url' => $url]));
        }
        return $this->saveNew($imageName, $imageData, $type);
    }

    /**
     * Saves a new image
     *
     * @param  string $imageName
     * @param  string $imageData
     * @param  string $type
     * @param  int    $uploadedTo
     * @return Image
     * @throws ImageUploadException
     */
    private function saveNew($imageName, $imageData, $type, $uploadedTo = 0)
    {
        $storage = $this->getStorage($type);
        $secureUploads = setting('app-secure-images');
        $imageName = str_replace(' ', '-', $imageName);

        $imagePath = '/uploads/images/' . $type . '/' . Date('Y-m-M') . '/';

        while ($storage->exists($imagePath . $imageName)) {
            $imageName = \Illuminate\Support\Str::random(3) . $imageName;
        }

        $fullPath = $imagePath . $imageName;
        if ($secureUploads) {
            $fullPath = $imagePath . \Illuminate\Support\Str::random(16) . '-' . $imageName;
        }

        try {
            $storage->put($fullPath, $imageData);
            $storage->setVisibility($fullPath, 'public');
        } catch (Exception $e) {
            throw new ImageUploadException(trans('errors.path_not_writable', ['filePath' => $fullPath]));
        }

        $imageDetails = [
            'name'       => $imageName,
            'path'       => $fullPath,
            'url'        => $this->getPublicUrl($fullPath),
            'type'       => $type,
            'uploaded_to' => $uploadedTo
        ];

        if (user()->id !== 0) {
            $userId = user()->id;
            $imageDetails['created_by'] = $userId;
            $imageDetails['updated_by'] = $userId;
        }

        $image = $this->image->newInstance();
        $image->forceFill($imageDetails)->save();
        return $image;
    }


    /**
     * Checks if the image is a gif. Returns true if it is, else false.
     *
     * @param  Image $image
     * @return boolean
     */
    protected function isGif(Image $image)
    {
        return strtolower(pathinfo($image->path, PATHINFO_EXTENSION)) === 'gif';
    }

    /**
     * Get the thumbnail for an image.
     * If $keepRatio is true only the width will be used.
     * Checks the cache then storage to avoid creating / accessing the filesystem on every check.
     *
     * @param  Image $image
     * @param  int   $width
     * @param  int   $height
     * @param  bool  $keepRatio
     * @return string
     * @throws Exception
     * @throws ImageUploadException
     */
    public function getThumbnail(Image $image, $width = 220, $height = 220, $keepRatio = false)
    {
        if ($keepRatio && $this->isGif($image)) {
            return $this->getPublicUrl($image->path);
        }

        $thumbDirName = '/' . ($keepRatio ? 'scaled-' : 'thumbs-') . $width . '-' . $height . '/';
        $imagePath = $image->path;
        $thumbFilePath = dirname($imagePath) . $thumbDirName . basename($imagePath);

        if ($this->cache->has('images-' . $image->id . '-' . $thumbFilePath) && $this->cache->get('images-' . $thumbFilePath)) {
            return $this->getPublicUrl($thumbFilePath);
        }

        $storage = $this->getStorage($image->type);
        if ($storage->exists($thumbFilePath)) {
            return $this->getPublicUrl($thumbFilePath);
        }

        try {
            $thumb = $this->imageTool->make($storage->get($imagePath));
        } catch (Exception $e) {
            if ($e instanceof \ErrorException || $e instanceof NotSupportedException) {
                throw new ImageUploadException(trans('errors.cannot_create_thumbs'));
            }
            throw $e;
        }

        if ($keepRatio) {
            $thumb->resize(
                $width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                }
            );
        } else {
            $thumb->fit($width, $height);
        }

        $thumbData = (string)$thumb->encode();
        $storage->put($thumbFilePath, $thumbData);
        $storage->setVisibility($thumbFilePath, 'public');
        $this->cache->put('images-' . $image->id . '-' . $thumbFilePath, $thumbFilePath, 60 * 72);

        return $this->getPublicUrl($thumbFilePath);
    }

    /**
     * Get the raw data content from an image.
     *
     * @param  Image $image
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getImageData(Image $image)
    {
        $imagePath = $image->path;
        $storage = $this->getStorage();
        return $storage->get($imagePath);
    }

    /**
     * Destroy an image along with its revisions, thumbnails and remaining folders.
     *
     * @param Image $image
     *
     * @throws Exception
     *
     * @return void
     */
    public function destroy(Image $image): void
    {
        $this->destroyImagesFromPath($image->path);
        $image->delete();
    }

    /**
     * Destroys an image at the given path.
     * Searches for image thumbnails in addition to main provided path..
     *
     * @param string $path
     *
     * @return true
     */
    protected function destroyImagesFromPath(string $path): bool
    {
        $storage = $this->getStorage();

        $imageFolder = dirname($path);
        $imageFileName = basename($path);
        $allImages = collect($storage->allFiles($imageFolder));

        // Delete image files
        $imagesToDelete = $allImages->filter(
            function ($imagePath) use ($imageFileName) {
                $expectedIndex = strlen($imagePath) - strlen($imageFileName);
                return strpos($imagePath, $imageFileName) === $expectedIndex;
            }
        );
        $storage->delete($imagesToDelete->all());

        // Cleanup of empty folders
        $foldersInvolved = array_merge([$imageFolder], $storage->directories($imageFolder));
        foreach ($foldersInvolved as $directory) {
            if ($this->isFolderEmpty($directory)) {
                $storage->deleteDirectory($directory);
            }
        }

        return true;
    }

    /**
     * Get the URL to fetch avatars from.
     *
     * @return string|mixed
     */
    protected function getAvatarUrl()
    {
        $url = trim(\Illuminate\Support\Facades\Config::get('services.avatar_url'));

        if (empty($url) && !\Illuminate\Support\Facades\Config::get('services.disable_services')) {
            $url = 'https://www.gravatar.com/avatar/${hash}?s=${size}&d=identicon';
        }

        return $url;
    }

    /**
     * Gets a public facing url for an image by checking relevant environment variables.
     *
     * @param  string $filePath
     * @return string
     */
    private function getPublicUrl($filePath)
    {
        if ($this->storageUrl === null) {
            $storageUrl = \Illuminate\Support\Facades\Config::get('filesystems.url');

            // Get the standard public s3 url if s3 is set as storage type
            // Uses the nice, short URL if bucket name has no periods in otherwise the longer
            // region-based url will be used to prevent http issues.
            if ($storageUrl == false && \Illuminate\Support\Facades\Config::get('filesystems.default') === 's3') {
                $storageDetails = \Illuminate\Support\Facades\Config::get('filesystems.disks.s3');
                if (strpos($storageDetails['bucket'], '.') === false) {
                    $storageUrl = 'https://' . $storageDetails['bucket'] . '.s3.amazonaws.com';
                } else {
                    $storageUrl = 'https://s3-' . $storageDetails['region'] . '.amazonaws.com/' . $storageDetails['bucket'];
                }
            }
            $this->storageUrl = $storageUrl;
        }

        $basePath = ($this->storageUrl == false) ? baseUrl('/') : $this->storageUrl;
        return rtrim($basePath, '/') . $filePath;
    }
}
