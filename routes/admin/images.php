<?php
/**
 * Admin
 */
/*
|--------------------------------------------------------------------------
| Images
|--------------------------------------------------------------------------
*/
Route::resource('images', 'ImagesController', ['except' => ['show']]);
Route::post('images/search', 'ImagesController@search');
Route::post('images/upload', 'ImagesController@upload');

/*
|--------------------------------------------------------------------------
| Files
|--------------------------------------------------------------------------
*/

Route::get('files/remove/{id}', 'FilesController@remove');
Route::post('files/upload', 'FilesController@upload');
Route::post('files/search', 'FilesController@search');

Route::resource('files', 'FilesController', ['except' => ['show']]);

// @todo media-manager
// // Admin Media
Route::group(
    [
    'as'     => 'media.',
    'prefix' => 'media',
    ], function () {
        // Route::get('medias', 'MediaController@medias')->name('files');
                // Route::get('/', ['uses' => 'HomeController@medias',              'as' => 'index']);
                // Route::post('files', ['uses' => 'HomeController@medias',              'as' => 'files']);
                // Route::post('new_folder', ['uses' => 'HomeController@medias',         'as' => 'new_folder']);
                // Route::post('delete_file_folder', ['uses' => 'HomeController@medias', 'as' => 'delete']);
                // Route::post('move_file', ['uses' => 'HomeController@medias',          'as' => 'move']);
                // Route::post('rename_file', ['uses' => 'HomeController@medias',        'as' => 'rename']);
                // Route::post('upload', ['uses' => 'HomeController@medias',             'as' => 'upload']);
                // Route::post('crop', ['uses' => 'HomeController@medias',             'as' => 'crop']);
        Route::get('/', ['uses' => 'MediaController@index',              'as' => 'index']);
        Route::post('files', ['uses' => 'MediaController@files',              'as' => 'files']);
        Route::post('new_folder', ['uses' => 'MediaController@new_folder',         'as' => 'new_folder']);
        Route::post('delete_file_folder', ['uses' => 'MediaController@delete', 'as' => 'delete']);
        Route::post('move_file', ['uses' => 'MediaController@move',          'as' => 'move']);
        Route::post('rename_file', ['uses' => 'MediaController@rename',        'as' => 'rename']);
        Route::post('upload', ['uses' => 'MediaController@upload',             'as' => 'upload']);
        Route::post('crop', ['uses' => 'MediaController@crop',             'as' => 'crop']);
    }
);


Route::get('encode/{id}/progress', [
    'as' => 'encode@progress',
    'uses' => 'Encoder@progress',
]);
Route::post('encode/notify', [
    'as' => 'encode@notify',
    'uses' => 'Encoder@notify',
]);