<?php

namespace MediaManager;

use App;
use Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Log;

use MediaManager\Facades\MediaManager as MediaManagerFacade;

use MediaManager\Services\FileService;
use MediaManager\Services\MediaManagerService;
use Muleta\Traits\Providers\ConsoleTools;

use Route;

class MediaManagerProvider extends ServiceProvider
{
    use ConsoleTools;

    public $packageName = 'media-manager';
    const pathVendor = 'sierratecnologia/media-manager';

    use ConsoleTools;

    public static $aliasProviders = [
        'MediaManager' => \MediaManager\Facades\MediaManager::class,
        'FileService' => FileService::class,
    ];

    public static $providers = [

        \Tracking\TrackingProvider::class,

        /**
         * Externos
         */
        // \CipeMotion\Medialibrary\ServiceProvider::class,
        \SierraTecnologia\Crypto\CryptoProvider::class,
        \Intervention\Image\ImageServiceProvider::class,
        \Spatie\MediaLibrary\MediaLibraryServiceProvider::class,

        \CipeMotion\Medialibrary\ServiceProvider::class,
    ];

    /**
     * Rotas do Menu
     */
    public static $menuItens = [
        [
            'text' => 'Fotografia',
            'icon' => 'fas fa-fw fa-search',
            'icon_color' => "blue",
            'label_color' => "success",
            'section' => "admin",
            'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        ],

        'Fotografia' => [
            // 'MediaManager' => [
                [
                    'text'        => 'Biblioteca',
                    'route'       => 'admin.media-manager.medias',
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'section'       => 'admin',
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
                // ],
            // 'MediaManager' => [
                [
                    'text'        => 'Imagens',
                    'route'       => 'admin.media-manager.images.index', // @todo
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'section'       => 'admin',
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Files',
                    'route'       => 'admin.media-manager.files.index', // @todo
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'section'       => 'admin',
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
        ]
        
    ];

    /**
     * Alias the services in the boot.
     */
    public function boot()
    {
        
        // Register configs, migrations, etc
        $this->registerDirectories();

        // Rotas
        $this->app->booted(
            function () {
                $this->routes();
            }
        );

        $this->loadLogger();
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        /**
         * MediaManager Routes
         */
        $this->loadRoutesForRiCa(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'routes');
    }

    /**
     * Register the services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getPublishesPath('config'.DIRECTORY_SEPARATOR.'sitec'.DIRECTORY_SEPARATOR.'media-manager.php'), 'sitec.media-manager');
        $this->mergeConfigFrom($this->getPublishesPath('config/encode.php'), 'encode');
        $this->mergeConfigFrom($this->getPublishesPath('config/image.php'), 'image');
        $this->mergeConfigFrom($this->getPublishesPath('config/imagecache.php'), 'imagecache');
        $this->mergeConfigFrom($this->getPublishesPath('config/media-library.php'), 'media-library');
        $this->mergeConfigFrom($this->getPublishesPath('config/messenger.php'), 'messenger');
        $this->mergeConfigFrom($this->getPublishesPath('config/mime.php'), 'mime');
        

        $this->setProviders();

        $this->app->singleton(
            'media-manager',
            function () {
                return new MediaManager();
            }
        );

        $this->app->bind(
            'FileService',
            function ($app) {
                return new FileService();
            }
        );
        
        // Events
        $this->app['events']->listen(
            'eloquent.saving:*',
            '\MediaManager\Observers\Encoding@onSaving'
        );
        $this->app['events']->listen(
            'eloquent.deleted:*',
            '\MediaManager\Observers\Encoding@onDeleted'
        );
        /*
        |--------------------------------------------------------------------------
        | Register the Utilities
        |--------------------------------------------------------------------------
        */
        /**
         * Singleton MediaManager
         */
        $this->app->singleton(
            MediaManagerService::class,
            function ($app) {
                Log::channel('sitec-media-manager')->info('Singleton MediaManager');
                return new MediaManagerService(\Illuminate\Support\Facades\Config::get('sitec.media-manager'));
            }
        );

        // Register commands
        $this->registerCommandFolders(
            [
            base_path('vendor/sierratecnologia/media-manager/src/Console/Commands') => '\MediaManager\Console\Commands',
            ]
        );

        // /**
        //  * Helpers
        //  */
        // Aqui noa funciona
        // if (!function_exists('media-manager_asset')) {
        //     function media-manager_asset($path, $secure = null)
        //     {
        //         return route('media-manager.assets').'?path='.urlencode($path);
        //     }
        // }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'media-manager',
        ];
    }

    /**
     * Register configs, migrations, etc
     *
     * @return void
     */
    public function registerDirectories()
    {
        // Publish config files
        $this->publishes(
            [
            // Paths
            $this->getPublishesPath('config'.DIRECTORY_SEPARATOR.'sitec') => config_path('sitec'),
            $this->getPublishesPath('config/encode.php') => config_path('encode.php'),
            $this->getPublishesPath('config/image.php') => config_path('image.php'),
            $this->getPublishesPath('config/imagecache.php') => config_path('imagecache.php'),
            $this->getPublishesPath('config/media-library.php') => config_path('media-library.php'),
            $this->getPublishesPath('config/messenger.php') => config_path('messenger.php'),
            $this->getPublishesPath('config/mime.php') => config_path('mime.php'),
            ],
            ['config',  'sitec', 'sitec-config']
        );

        // // Publish media-manager css and js to public directory
        // $this->publishes([
        //     $this->getDistPath('media-manager') => public_path('assets/media-manager')
        // ], ['public',  'sitec', 'sitec-public']);

        $this->loadViews();
        $this->loadTranslations();


        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations');
    }

    private function loadViews()
    {
        // View namespace
        $viewsPath = $this->getResourcesPath('views');
        $this->loadViewsFrom($viewsPath, 'media-manager');
        $this->publishes(
            [
            $viewsPath => base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'media-manager'),
            ],
            ['views',  'sitec', 'sitec-views', 'media-manager']
        );
    }
    
    private function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'media-manager')
            ],
            ['lang',  'sitec', 'media-manager']
        );

        // Load translations
        $this->loadTranslationsFrom($this->getResourcesPath('lang'), 'media-manager');
    }


    /**
     *
     */
    private function loadLogger()
    {
        Config::set(
            'logging.channels.sitec-media-manager',
            [
            'driver' => 'single',
            'path' => storage_path('logs'.DIRECTORY_SEPARATOR.'sitec-media-manager.log'),
            'level' => env('APP_LOG_LEVEL', 'debug'),
            ]
        );
    }
}
