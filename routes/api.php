<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::any('dispositivo', 'ComputerController@register');
Route::any('vlc', 'ComputerController@vlc');
Route::any('playlist', 'PlaylistController@show');
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group([/*'middleware' => 'admin', */'prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    // Route::get('/', 'HomeController@index')->name('home');
    // Route::get('/home', 'HomeController@index');
    // Route::resource('playlists', 'PlaylistController');
    // Route::post('/playlists/{id}/addvideo', 'PlaylistController@includeVideo')->name('playlists.addvideo');
    // Route::post('/playlists/{id}/removevideo', 'PlaylistController@removeVideo')->name('playlists.removevideo');
    // Route::get('/playlists/{id}/up/{position}', 'PlaylistController@videoUp')->name('playlists.up');
    // Route::get('/playlists/{id}/down/{position}', 'PlaylistController@videoDown')->name('playlists.down');
    // Route::post('/groups/{id}/adddispositivo', 'GroupController@adddispositivo')->name('groups.adddispositivo');
    // Route::post('/groups/{id}/changeplaylist', 'GroupController@changeplaylist')->name('groups.changeplaylist');
    
    Route::resource('groups', 'GroupController');
    Route::post('/groups/{group}/{computer}/adddispositivo', 'GroupController@adddispositivo')->name('groups.adddispositivo');
    Route::post('/groups/{group}/{computer}/rmdispositivo', 'GroupController@rmdispositivo')->name('groups.rmdispositivo');
    Route::resource('computers', 'ComputerController');
    // Route::get('/pendentes', 'ComputerController@pendentes')->name('pendentes.index');
    // Route::post('/active/{id}', 'ComputerController@active')->name('computers.active');


    // Route::get('help', 'PageController@help')->name('help');
    // Route::get('changelog', 'PageController@changelog')->name('changelog');
    // // // Admin Media
    // Route::group(
    //     [
    //     'as'     => 'media.',
    //     'prefix' => 'media',
    //     ], function () {
    //         Route::get('medias', 'HomeController@medias')->name('files');
                   
    //         Route::get('/', ['uses' => 'MediaController@index',              'as' => 'index']);
    //         Route::post('files', ['uses' => 'MediaController@files',              'as' => 'files']);
    //         Route::post('new_folder', ['uses' => 'MediaController@new_folder',         'as' => 'new_folder']);
    //         Route::post('delete_file_folder', ['uses' => 'MediaController@delete', 'as' => 'delete']);
    //         Route::post('move_file', ['uses' => 'MediaController@move',          'as' => 'move']);
    //         Route::post('rename_file', ['uses' => 'MediaController@rename',        'as' => 'rename']);
    //         Route::post('upload', ['uses' => 'MediaController@upload',             'as' => 'upload']);
    //         Route::post('crop', ['uses' => 'MediaController@crop',             'as' => 'crop']);
    //     }
    // );
    
    
    // Route::get('encode/{id}/progress', [
    //     'as' => 'encode@progress',
    //     'uses' => 'Encoder@progress',
    // ]);
    // Route::post('encode/notify', [
    //     'as' => 'encode@notify',
    //     'uses' => 'Encoder@notify',
    // ]);
});
