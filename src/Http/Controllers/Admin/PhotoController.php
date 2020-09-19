<?php 

namespace MediaManager\Http\Controllers\Admin;

use Siravel\Http\Controllers\Features\AdminController;
use MediaManager\Models\Photo;
use MediaManager\Models\PhotoAlbum;
use Translation\Models\Language;
use App\Http\Requests\Admin\PhotoRequest;
use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Helpers\Thumbnail;
use Illuminate\Support\Facades\DB;
use Datatables;

class PhotoController extends Controller
{

    public function __construct()
    {
        view()->share('type', 'photo');
    }

    /**
     * Show a list of all the photo posts.
     *
     * @return View
     */
    public function index()
    {
        // Show the page
        return view('features.girl.photo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $languages = Language::lists('name', 'id')->toArray();
        $photoalbums = PhotoAlbum::lists('name', 'id')->toArray();
        // Show the page
        return view('features.girl.photo.create_edit', compact('languages', 'photoalbums'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PhotoRequest $request)
    {
        $photo = new Photo($request->except('image'));
        $photo->user_id = Auth::id();

        $picture = "";
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = sha1($filename . time()) . '.' . $extension;
        }
        $photo->filename = $picture;
        $photo->save();

        if ($request->hasFile('image')) {
            $photoalbum = PhotoAlbum::find($request->photo_album_id);
            $destinationPath = public_path() . '/appfiles/photoalbum/' . $photoalbum->folder_id . '/';
            $request->file('image')->move($destinationPath, $picture);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Photo $photo)
    {
        $languages = Language::lists('name', 'id')->toArray();
        $photoalbums = PhotoAlbum::lists('name', 'id')->toArray();
        return view('features.girl.photo.create_edit', compact('photo', 'languages', 'photoalbums'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(PhotoRequest $request, Photo $photo)
    {
        $photo->user_id_edited = Auth::id();
        $picture = $photo->filename;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = sha1($filename . time()) . '.' . $extension;
        }
        $photo->filename = $picture;
        $photo->update($request->except('image'));

        if ($request->hasFile('image')) {
            $photoalbum = PhotoAlbum::find($request->photo_album_id);
            $destinationPath = public_path() . '/appfiles/photoalbum/' . $photoalbum->folder_id . '/';
            $request->file('image')->move($destinationPath, $picture);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */

    public function delete(Photo $photo)
    {
        return view('features.girl.photo.delete', compact('photo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy(Photo $photo)
    {
        $photo->delete();
    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $photos = Photo::with('album','language')
            ->get()
            ->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'album_cover' => $photo->album_cover,
                    'slider' => $photo->slider,
                    'album' => isset($photo->album) ? $photo->album->title : "",
                    'language' => isset($photo->language) ? $photo->language->name : "",
                    'created_at' => $photo->created_at->format('d.m.Y.'),
                ];
            });
        return Datatables::of($photos)
            ->add_column('actions', '<a href="{{{ url(\'girl/photo/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm iframe" ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("girl/modal.edit") }}</a>
                <a href="{{{ url(\'girl/photo/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger iframe"><span class="glyphicon glyphicon-trash"></span> {{ trans("girl/modal.delete") }}</a>
                <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->remove_column('id')
            ->make();
    }
}
