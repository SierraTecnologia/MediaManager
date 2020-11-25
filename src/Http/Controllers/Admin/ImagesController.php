<?php

namespace MediaManager\Http\Controllers\Admin;

use Config;
use Crypto;
use Muleta\Services\ValidationService;
use FileService;
use Illuminate\Http\Request;
use MediaManager\Http\Requests\ImagesRequest;
use MediaManager\Models\Image;
use MediaManager\Repositories\ImageRepository;
use Siravel;
use Storage;
use Muleta\Services\RiCaResponseService;

class ImagesController extends Controller
{
    const VIEWS = "media-manager::admin.images";
    
    public function __construct(ImageRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }


    /**
     * Store a newly created Files in storage.
     *
     * @param FileRequest $request
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        // @todo
        $validation = app(ValidationService::class)->check([
            // 'file' => ['required'],   // @todo Voltar pra validar aqui
        ]);


        if (!$validation['errors']) {
            $file = $request->file('file');

            // Da bill aqui @todo
            $fileSaved = app(FileService::class)->saveFile($file, 'public/images', [], true);

            $fileSaved['name'] = Crypto::encrypt($fileSaved['name']);
            try {
                $fileSaved['mime'] = $file->getClientMimeType();
                $fileSaved['size'] = $file->getClientSize();
            } catch (\Throwable $th) {
                \Log::warning($th->getMessage());
            }
            $response = app(RiCaResponseService::class)->apiResponse('success', $fileSaved);
        } else {
            $response = app(RiCaResponseService::class)->apiErrorResponse($validation['errors'], $validation['inputs']);
        }

        return $response;
    }

    /**
     * Bulk image delete
     *
     * @param string $ids
     *
     * @return Redirect
     */
    public function bulkDelete($ids)
    {
        $ids = explode('-', $ids);

        foreach ($ids as $id) {
            $image = $this->repository->find($id);

            if (is_file(storage_path($image->location))) {
                Storage::delete($image->location);
            } else {
                Storage::disk(Config::get('siravel.storage-location', 'local'))->delete($image->location);
            }

            $image->delete();
        }

        Siravel::notification('Bulk Image deletes completed successfully.', 'success');

        return redirect(route('root.images.index'));
    }

    /*
    |--------------------------------------------------------------------------
    | Api
    |--------------------------------------------------------------------------
    */

    /**
     * Display the specified Images.
     *
     * @return Response
     */
    public function apiList(Request $request)
    {
        if (config('siravel.api-key') != $request->header('siravel')) {
            return app(RiCaResponseService::class)->apiResponse('error', []);
        }

        $images =  $this->repository->apiPrepared();

        return app(RiCaResponseService::class)->apiResponse('success', $images);
    }

    /**
     * Store a newly created Images in storage.
     *
     * @param ImagesRequest $request
     *
     * @return Response
     */
    public function apiStore(Request $request)
    {
        $image = $this->repository->apiStore($request->all());

        return app(RiCaResponseService::class)->apiResponse('success', $image);
    }


    /**
     * DESCOMENTEI
     */
    /**
     * Display a listing of the Images. @todo não pode ser diferente de SUpport:base
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $result = $this->repository->paginated();

        return view('media-manager::admin.images.index')
            ->with('images', $result)
            ->with('results', $result)
            ->with('pagination', $result->render());
    }

    /**
     * Search. @todo não pode ser diferente de SUpport:base
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request)
    {
        $input = $request->all();

        $result = $this->repository->search($input);

        return view('media-manager::admin.images.index')
            ->with('images', $result[0]->get())
            ->with('results', $result[0]->get())
            ->with('pagination', $result[2])
            ->with('term', $result[1]);
    }

    /**
     * Show the form for creating a new Images.
     *
     * @return Response
     */
    public function create()
    {
        return view('media-manager::admin.images.create');
    }

    /**
     * Store a newly created Images in storage.
     *
     * @param ImagesRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validation = app(ValidationService::class)->check(['location' => 'required']);
            if (!$validation['errors']) {
                foreach ($request->input('location') as $image) {
                    $imageSaved = $this->repository->store([
                        'location' => $image,
                        'is_published' => $request->input('is_published'),
                        'tags' => $request->input('tags'),
                    ]);
                }

                Siravel::notification('Image saved successfully.', 'success');

                if (!$imageSaved) {
                    Siravel::notification('Image was not saved.', 'danger');
                }
            } else {
                Siravel::notification('Image could not be saved', 'danger');

                return $validation['redirect'];
            }
        } catch (Exception $e) {
            Siravel::notification($e->getMessage() ?: 'Image could not be saved.', 'danger');
        }

        return redirect(route('root.images.index'));
    }

    /**
     * Show the form for editing the specified Images.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $images = $this->repository->find($id);

        if (empty($images)) {
            Siravel::notification('Image not found', 'warning');

            return redirect(route('root.images.index'));
        }

        return view('media-manager::admin.images.edit')->with('images', $images);
    }

    /**
     * Update the specified Images in storage.
     *
     * @param int           $id
     * @param ImagesRequest $request
     *
     * @return Response
     */
    public function update($id, ImagesRequest $request)
    {
        try {
            $images = $this->repository->find($id);

            Siravel::notification('Image updated successfully.', 'success');

            if (empty($images)) {
                Siravel::notification('Image not found', 'warning');

                return redirect(route('root.images.index'));
            }

            $images = $this->repository->update($images, $request->all());

            if (!$images) {
                Siravel::notification('Image could not be updated', 'danger');
            }
        } catch (Exception $e) {
            Siravel::notification($e->getMessage() ?: 'Image could not be saved.', 'danger');
        }

        return redirect(route('root.images.edit', $id));
    }

    /**
     * Remove the specified Images from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $image = $this->repository->find($id);

        if (is_file(storage_path($image->location))) {
            Storage::delete($image->location);
        } else {
            Storage::disk(Config::get('siravel.storage-location', 'local'))->delete($image->location);
        }

        if (empty($image)) {
            Siravel::notification('Image not found', 'warning');

            return redirect(route('root.images.index'));
        }

        $image->forgetCache();
        $image->delete();

        Siravel::notification('Image deleted successfully.', 'success');

        return redirect(route('root.images.index'));
    }


}
