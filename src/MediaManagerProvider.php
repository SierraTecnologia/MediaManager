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
            'text' => 'Endotera',
            'icon' => 'fas fa-fw fa-search',
            'icon_color' => "blue",
            'label_color' => "success",
            'feature' => 'midia',
            'section' => "admin",
            'order' => 2400,
            'level'       => 2, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        ],
        'Endotera' => [
            // 'MediaManager' => [
                // ],
            // 'MediaManager' => [

                [
                    'text'        => 'Playlists',
                    'route'       => 'admin.media-manager.playlists.index',
                    'icon'        => 'fas fa-fw oi oi-media-play',
                    'icon_color'  => 'green',
                    'section' => "admin",
                    'label_color' => 'success',
                    'level' => 1
                ],
                [
                    'text'        => 'Videos',
                    'route'       => 'admin.media-manager.media.index',
                    'icon'        => 'fas fa-fw fa-video',
                    'icon_color'  => 'red',
                    'label_color' => 'success',
                    'section' => "admin",
                    'level' => 1
                ],
                [
                    'text'        => 'Midias',
                    'route'       => 'media-manager.medias',
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'feature' => 'midia',
                    'section'       => 'painel',
                    'order' => 600,
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
                // [
                //     'text'        => 'Imagens',
                //     'route'       => 'admin.media-manager.images.index', // @todo
                //     'icon'        => 'fas fa-fw fa-gavel',
                //     'icon_color'  => 'blue',
                //     'label_color' => 'success',
                //     'feature' => 'midia',
                //     'section'       => 'admin',
                //     'order' => 2400,
                //     // 'access' => \Porteiro\Models\Role::$ADMIN
                // ],
                // [
                //     'text'        => 'Files',
                //     'route'       => 'admin.media-manager.files.index', // @todo
                //     'icon'        => 'fas fa-fw fa-gavel',
                //     'icon_color'  => 'blue',
                //     'label_color' => 'success',
                //     'feature' => 'midia',
                //     'section'       => 'admin',
                //     'order' => 2400,
                //     // 'access' => \Porteiro\Models\Role::$ADMIN
                // ],
            ],
        // [
        //     'text'        => 'Painel',
        //     'url'         => 'admin',
        //     'icon'        => 'fas fa-fw fa-tachometer-alt',
        //     // 'icon_color'  => 'blue',
        //     // 'label_color' => 'success',
        // ],
        // [
        //     'header' => 'Dispositivos',
        //     'level' => 1,
        // ],
        // [
        //     'text'        => 'Ativos',
        //     'route'       => 'admin.computers.index',
        //     'icon'        => 'fas fa-fw fa-desktop',
        //     'icon_color'  => 'green',
        //     'label_color' => 'success',
        //     'level' => 1,
        // ],
        // [
        //     'text'        => 'Pendentes de Ativação',
        //     'route'       => 'admin.pendentes.index',
        //     'icon'        => 'fas fa-fw fa-desktop',
        //     'icon_color'  => 'red',
        //     'label_color' => 'danger',
        //     'label'        => '0',
        //     'level' => 2
        // ],
        // [
        //     'text'        => 'Grupos de dispositivos',
        //     'route'       => 'admin.groups.index',
        //     'icon'        => 'fas fa-fw fa-users',
        //     'icon_color'  => 'yellow',
        //     'label_color' => 'success',
        //     'level' => 1,
        // ],
        // [
        //     'header' => 'Medias',
        //     'level' => 2
        // ],
        // [
        //     'text'        => 'Playlists',
        //     'route'       => 'admin.playlists.index',
        //     'icon'        => 'fas fa-fw oi oi-media-play',
        //     'icon_color'  => 'green',
        //     'label_color' => 'success',
        //     'level' => 1
        // ],
        // [
        //     'text'        => 'Videos',
        //     'route'       => 'admin.media.index',
        //     'icon'        => 'fas fa-fw fa-video',
        //     'icon_color'  => 'red',
        //     'label_color' => 'success',
        //     'level' => 1
        // ],
        // [
        //     'text'        => 'Midias',
        //     'route'       => 'media-manager.medias',
        //     'icon'        => 'fas fa-fw fa-gavel',
        //     'icon_color'  => 'blue',
        //     'label_color' => 'success',
        //     'feature' => 'midia',
        //     'section'       => 'painel',
        //     'order' => 600,
        //     // 'access' => \Porteiro\Models\Role::$ADMIN
        // ],
        [
            'text' => 'Fotografia',
            'icon' => 'fas fa-fw fa-search',
            'icon_color' => "blue",
            'label_color' => "success",
            'feature' => 'midia',
            'section' => "admin",
            'order' => 2400,
            'level'       => 2, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        ],
        'Fotografia' => [
            // 'MediaManager' => [
                // ],
            // 'MediaManager' => [
                [
                    'text'        => 'Imagens',
                    'route'       => 'admin.media-manager.images.index', // @todo
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'feature' => 'midia',
                    'section'       => 'admin',
                    'order' => 2400,
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Files',
                    'route'       => 'admin.media-manager.files.index', // @todo
                    'icon'        => 'fas fa-fw fa-gavel',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'feature' => 'midia',
                    'section'       => 'admin',
                    'order' => 2400,
                    // 'access' => \Porteiro\Models\Role::$ADMIN
                ],
        ]
        
    ];

    /**
     * Alias the services in the boot.
     *
     * @return void
     */
    public function boot(): void
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

    private function loadViews(): void
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
    
    private function loadTranslations(): void
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
     * @return void
     */
    private function loadLogger(): void
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
