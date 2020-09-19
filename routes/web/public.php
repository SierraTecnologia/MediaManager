<?php

/**
 *
 */
Route::get('assets', ['uses' => 'BaseController@assets', 'as' => 'assets']);
Route::get('medias', 'HomeController@medias')->name('medias');
/**
 * Midia @todo
 */
// Route::group(['namespace' => 'Midia', 'as' => 'midia.'], function () {
    Route::get('gallery', 'GalleryController@all');
    Route::get('gallery/{tag}', 'GalleryController@show');
    
    Route::get('midia-preview/{midiaId}', 'MidiaController@asPreview');
    Route::get('midia-full/{midiaId}', 'MidiaController@asFull');
    Route::get('midia-download/{midiaId}/{encRealFileName}', 'MidiaController@asDownload');
// });
