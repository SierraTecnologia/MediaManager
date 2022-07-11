<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/vlc', 'MediaController@vlc');

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
*/

Route::get('public-preview/{encFileName}', 'AssetController@asPreview')->name('asset.preview');
Route::get('public-asset/{encFileName}', 'AssetController@asPublic')->name('asset.public');
Route::get('public-download/{encFileName}/{encRealFileName}', 'AssetController@asDownload')->name('asset.download');
Route::get('asset/{path}/{contentType}', 'AssetController@asset')->name('asset.show');


Route::get('assets', ['uses' => 'BaseController@assets', 'as' => 'assets']);
Route::get('facilitador-assets/{path?}', ['uses' => 'SitecFeatureController@assets', 'as' => 'facilitador_assets']);
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index');
    Route::resource('playlists', 'PlaylistController');
    Route::post('/playlists/{id}/addvideo', 'PlaylistController@includeVideo')->name('playlists.addvideo');
    Route::delete('/playlists/{id}/removevideo', 'PlaylistController@removeVideo')->name('playlists.removevideo');
    Route::get('/playlists/{id}/up/{position}', 'PlaylistController@videoUp')->name('playlists.up');
    Route::get('/playlists/{id}/down/{position}', 'PlaylistController@videoDown')->name('playlists.down');
    Route::post('/groups/{id}/adddispositivo', 'GroupController@adddispositivo')->name('groups.adddispositivo');
    Route::post('/groups/{id}/changeplaylist', 'GroupController@changeplaylist')->name('groups.changeplaylist');
    
    Route::resource('groups', 'GroupController');
    Route::resource('computers', 'ComputerController');
    Route::get('/pendentes', 'ComputerController@pendentes')->name('pendentes.index');
    Route::any('/active/{id}', 'ComputerController@active')->name('computers.active');

    // // Admin Media
    Route::group(
        [
        'as'     => 'media.',
        'prefix' => 'media',
        ], function () {
            Route::get('medias', 'HomeController@medias')->name('files');
                   
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
});


/*
|--------------------------------------------------------------------------
| Root Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'root', 'prefix' => 'root', 'as' => 'root.', 'namespace' => 'Root'], function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    // Route::resource('permissions', 'PermissionController');
});


/**
 * Teamwork routes
 */
Route::group(['prefix' => 'teams', 'namespace' => 'Teamwork'], function()
{
    Route::get('/', [App\Http\Controllers\Teamwork\TeamController::class, 'index'])->name('teams.index');
    Route::get('create', [App\Http\Controllers\Teamwork\TeamController::class, 'create'])->name('teams.create');
    Route::post('teams', [App\Http\Controllers\Teamwork\TeamController::class, 'store'])->name('teams.store');
    Route::get('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'edit'])->name('teams.edit');
    Route::put('edit/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'update'])->name('teams.update');
    Route::delete('destroy/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('switch/{id}', [App\Http\Controllers\Teamwork\TeamController::class, 'switchTeam'])->name('teams.switch');

    Route::get('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'show'])->name('teams.members.show');
    Route::get('members/resend/{invite_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'resendInvite'])->name('teams.members.resend_invite');
    Route::post('members/{id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'invite'])->name('teams.members.invite');
    Route::delete('members/{id}/{user_id}', [App\Http\Controllers\Teamwork\TeamMemberController::class, 'destroy'])->name('teams.members.destroy');

    Route::get('accept/{token}', [App\Http\Controllers\Teamwork\AuthController::class, 'acceptInvite'])->name('teams.accept_invite');
});
