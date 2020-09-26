<?php

return [

    /*
     * The disk on which to store added files and derived images by default. Choose
     * one or more of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('MEDIA_DISK', 'public'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 10,

    /*
     * This queue will be used to generate derived and responsive images.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => MediaManager\Models\Media::class,


    'media' => [
        // The allowed mimetypes to be uploaded through the media-manager.
        'allowed_mimetypes' => '*', //All types can be uploaded
        /*
        'allowed_mimetypes' => [
          'image/jpeg',
          'image/png',
          'image/gif',
          'image/bmp',
          'video/mp4',
        ],
        */
       //Path for media-manager. Relative to the filesystem.
       'path'                => '/',
       'show_folders'        => true,
       'allow_upload'        => true,
       'allow_move'          => true,
       'allow_delete'        => true,
       'allow_create_folder' => true,
       'allow_rename'        => true,
       'watermark'           => [
            'source'         => 'watermark.png',
            'position'       => 'bottom-left',
            'x'              => 0,
            'y'              => 0,
            'size'           => 15,
        ],
        'thumbnails'          => [
           [
                'type'  => 'fit',
                'name'  => 'fit-500',
                'width' => 500,
                'height'=> 500
           ],
        ]
    ],
    
    's3' => [
        /*
         * The domain that should be prepended when generating urls.
         */
        'domain' => 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com',
    ],

    'remote' => [
        /*
         * Any extra headers that should be included when uploading media to
         * a remote disk. Even though supported headers may vary between
         * different drivers, a sensible default has been provided.
         *
         * Supported by S3: CacheControl, Expires, StorageClass,
         * ServerSideEncryption, Metadata, ACL, ContentEncoding
         */
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

    'responsive_images' => [

        /*
         * This class is responsible for calculating the target widths of the responsive
         * images. By default we optimize for filesize and create variations that each are 20%
         * smaller than the previous one. More info in the documentation.
         *
         * https://docs.spatie.be/laravel-media-library/v7/advanced-usage/generating-responsive-images
         */
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,

        /*
         * By default rendering media to a responsive image will add some javascript and a tiny placeholder.
         * This ensures that the browser can already determine the correct layout.
         */
        'use_tiny_placeholders' => true,

        /*
         * This class will generate the tiny placeholder used for progressive image loading. By default
         * the media-library will use a tiny blurred jpg image.
         */
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    /*
     * When urls to files get generated, this class will be called. Leave empty
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => null,

    /*
     * Whether to activate versioning when urls to files get generated.
     * When activated, this attaches a ?v=xx query string to the URL.
     */
    'version_urls' => false,

    /*
     * The class that contains the strategy for determining a media file's path.
     */
    'path_generator' => null,

    /*
     * Medialibrary will try to optimize all converted images by removing
     * metadata and applying a little bit of compression. These are
     * the optimizers that will be used by default.
     */
    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '--strip-all', // this strips out all text information such as comments and EXIF data
            '--all-progressive', // this will make sure the resulting image is a progressive one
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0', // this will result in a non-interlaced, progressive scanned image
            '-o2', // this set the optimization level to two (multiple IDAT compression trials)
            '-quiet', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--disable=cleanupIDs', // disabling because it is known to cause troubles
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b', // required parameter for this package
            '-O3', // this produces the slowest but best results
        ],
    ],

    /*
     * These generators will be used to create an image of media files.
     */
    'image_generators' => [
        Spatie\MediaLibrary\ImageGenerators\FileTypes\Image::class,
        Spatie\MediaLibrary\ImageGenerators\FileTypes\Webp::class,
        Spatie\MediaLibrary\ImageGenerators\FileTypes\Pdf::class,
        Spatie\MediaLibrary\ImageGenerators\FileTypes\Svg::class,
        Spatie\MediaLibrary\ImageGenerators\FileTypes\Video::class,
    ],

    /*
     * The engine that should perform the image conversions.
     * Should be either `gd` or `imagick`.
     */
    'image_driver' => 'gd',

    /*
     * FFMPEG & FFProbe binaries paths, only used if you try to generate video
     * thumbnails and have installed the php-ffmpeg/php-ffmpeg composer
     * dependency.
     */
    'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    'ffprobe_path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),

    /*
     * The path where to store temporary files while performing image conversions.
     * If set to null, storage_path('media-library/temp') will be used.
     */
    'temporary_directory_path' => null,

    /*
     * Here you can override the class names of the jobs used by this package. Make sure
     * your custom jobs extend the ones provided by the package.
     */
    'jobs' => [
        'perform_conversions' => Spatie\MediaLibrary\Jobs\PerformConversions::class,
        'generate_responsive_images' => Spatie\MediaLibrary\Jobs\GenerateResponsiveImages::class,
    ],



    /*
    |--------------------------------------------------------------------------
    | FROM SIRAVEL
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    */

    'disk' => function () {
        return 'media';
    },

    'generator' => [

        'url' => CipeMotion\Medialibrary\Generators\AzureUrlGenerator::class,

        'path' => CipeMotion\Medialibrary\Generators\PathGenerator::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    'services' => [
        'dropbox' => [
            'key'    => env('MEDIALIBRARY_DROPBOX_KEY'),
            'secret' => env('MEDIALIBRARY_DROPBOX_SECRET'),
        ],

        'onedrive' => [
            'key'    => env('MEDIALIBRARY_ONEDRIVE_KEY'),
            'secret' => env('MEDIALIBRARY_ONEDRIVE_SECRET'),
        ],

        'googledrive' => [
            'key'    => env('MEDIALIBRARY_GOOGLE_DRIVE_KEY'),
            'secret' => env('MEDIALIBRARY_GOOGLE_DRIVE_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    'relations' => [

        'owner' => [

            'model' => 'App\Models\User',

            'resolver' => function () {
                return auth()->user();
            },

        ],

        'user' => [

            'model' => null,

            'resolver' => null,

        ],

        'attachment' => [

        //            'post' => 'Siravel\Models\Blog\Post',

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Files types
    |--------------------------------------------------------------------------
    |
    | {name} => [
    |
    |       'mimes'           => [
    |
    |           {extension} => {mime type}
    |
    |       ],
    |
    |       'transformations' => [
    |
    |           {name} => [
    |
    |               'transformer' => ITransformer::class,
    |               'queued'      => {(bool|string) boolean to indicate queue or not, string if on a custom queue}
    |               'config'      => {(array) an array with configuration}
    |
    |           ]
    |
    |       ],
    |
    |       'max_file_size'   => {(int) the max filesize in bytes, when exceeded a \Exception is thrown}
    | ]
    |
    */

    'file_types' => [

        'image' => [

            'mimes' => [

                'gif'  => 'image/gif',
                'png'  => 'image/png',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',

            ],

            'transformationGroups' => [

                'default' => [

                    'default',

                ],

            ],

            'thumb' => [

                'transformer' => CipeMotion\Medialibrary\Transformers\Image\ResizeImageTransformer::class,

                'queued' => false,

                'config' => [

                    'size' => [

                        'w' => 280,
                        'h' => 280,

                    ],

                    'fit' => true,

                    'aspect' => true,

                    'upsize' => true,

                ],

            ],

            'transformations' => [

                'default' => [

                    'transformer' => CipeMotion\Medialibrary\Transformers\Image\ResizeImageTransformer::class,

                    'queued' => true,

                    'config' => [

                        'size' => [

                            'w' => 900,

                        ],

                        'aspect' => true,

                        'upsize' => false,

                        'default' => true,

                    ],

                ],

            ],

            'max_file_size' => 10 * 1024 * 1024,

        ],

        'video' => [

            'mimes' => [

                'avi'   => [

                    'video/avi',
                    'video/msvideo',
                    'video/x-msvideo',

                ],
                'mpeg'  => 'video/mpeg',
                'mpeg4' => 'video/mp4v-es',
                'mp4'   => [

                    'video/mp4v-es',
                    'video/mp4',

                ],
                'mov'   => 'video/quicktime',
                'wmv'   => 'video/x-ms-wmv',
                'flv'   => 'video/x-flv',
                '3gpp'  => 'video/3gpp',
                'webm'  => 'video/webm',
                'ogg'   => [

                    'video/ogg',
                    'application/ogg',

                ],

            ],

            'wait_for_default_transformation' => true,

            'transformationGroups' => [

                'default' => [],

            ],

            'transformations' => [

                'default' => [

                    'transformer' => CipeMotion\Medialibrary\Transformers\Video\VideoToImagePreviewTransformer::class,

                    'queued' => true,

                    'config' => [

                        'thumb' => [

                            'size' => [

                                'w' => 500,
                                'h' => 500,

                            ],

                            'fit' => true,

                        ],

                        'preview' => [

                            'size' => [

                                'w' => 1280,
                                'h' => 720,

                            ],

                        ],

                        'video' => [

                            'codec'      => 'h264',
                            'resolution' => '1280x720',

                        ],

                        'audio' => [

                            'codec' => 'aac',

                        ],

                        'default' => true,

                    ],

                ],

            ],

            'max_file_size' => 256 * 1024 * 1024,

        ],

        'document' => [

            'mimes' => [

                'pdf'  => [

                    'application/pdf',
                    'application/x-pdf',
                    'application/acrobat',
                    'applications/vnd.pdf',
                    'text/pdf',
                    'text/x-pdf',
                    'application/download',
                    'application/x-download',
                    'application/save-as',

                ],
                'doc'  => 'application/msword',
                'dot'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'txt'  => 'text/plain',
                'pot'  => [

                    'application/mspowerpoint',
                    'application/vnd.ms-powerpoint',

                ],
                'ppa'  => [

                    'application/mspowerpoint',
                    'application/vnd.ms-powerpoint',

                ],
                'pps'  => [

                    'application/mspowerpoint',
                    'application/vnd.ms-powerpoint',

                ],
                'pws'  => 'application/vnd.ms-powerpoint',
                'ppt'  => [

                    'application/mspowerpoint',
                    'application/powerpoint',
                    'application/vnd.ms-powerpoint',
                    'application/x-mspowerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',

                ],
                'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'xls'  => 'application/vnd.ms-excel',
                'xlt'  => 'application/vnd.ms-excel',
                'xla'  => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],

            'thumb' => [

                'transformer' => CipeMotion\Medialibrary\Transformers\Document\DocumentToImagePreviewTransformer::class,

                'queued' => true,

                'config' => [

                    'size' => [

                        'w' => 280,
                        'h' => 280,

                    ],

                    'fit' => true,

                    //'aspect' => true,

                    //'upsize' => true

                ],

                'defaults' => [
                    'thumb'   => null, // File url
                    'preview' => null,
                ],

            ],

            'transformationGroups' => [

                'default' => [],

            ],

            'transformations' => [],

            'max_file_size' => 10 * 1024 * 1024,

        ],

        'audio' => [

            'mimes' => [

                'mp3' => [

                    'audio/mpeg3',
                    'audio/x-mpeg-3',

                ],
                'wav' => [

                    'audio/wav',
                    'audio/x-wav',

                ],
                'ogg' => 'application/ogg',
                'm4a' => [

                    'audio/mp4',
                    'audio/x-m4a',

                ],

            ],

            'transformationGroups' => [

                'default' => [],

            ],

            'transformations' => [],

            'max_file_size' => 50 * 1024 * 1024,

        ],

    ],
];
