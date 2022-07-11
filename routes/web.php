<?php

/**
 * Include App Routes
 */
$loadingRoutes = [
    'public',
    'endotera',
];

Route::group(
    ['middleware' => ['web']], function () use ($loadingRoutes) {

        // Route::prefix('media-manager')->group(
        //     function () use ($loadingRoutes) {
                Route::group(
                    ['as' => 'media-manager.'], function () use ($loadingRoutes) {

                        foreach ($loadingRoutes as $loadingRoute) {
                            include dirname(__FILE__) . DIRECTORY_SEPARATOR . "web". DIRECTORY_SEPARATOR . $loadingRoute.".php";
                        }    


                    }
                );
        //     }
        // );

    }
);