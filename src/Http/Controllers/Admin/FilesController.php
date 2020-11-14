<?php

namespace MediaManager\Http\Controllers\Admin;

use App\Http\Requests\FileRequest;
use Config;
use Crypto;
use Exception;
use Finder\Models\Digital\Midia\File;
use Illuminate\Http\Request;
use MediaManager\Repositories\FileRepository;
use MediaManager\Services\FileService;
use Muleta\Services\RiCaResponseService;
use Muleta\Services\ValidationService;
use Redirect;
use Response;
use Siravel;
use Storage;

class FilesController extends Controller
{
    public function __construct(
        FileRepository $repository,
        FileService $fileService,
        ValidationService $validationService,
        RiCaResponseService $siravelResponseService
    ) {
        parent::__construct();
        $this->repository = $repository;
        $this->fileService = $fileService;
        $this->validation = $validationService;
        $this->responseService = $siravelResponseService;
    }

    // /**
    //  * Display a listing of the Files.
    //  *
    //  * @param Request $request
    //  *
    //  * @return Response
    //  */
    // public function index(Request $request)
    // {
    //     $result = $this->repository->paginated();

    //     return view('media-manager::admin.files.index')
    //         ->with('files', $result)
    //         ->with('pagination', $result->render());
    // }

    // /**
    //  * Search.
    //  *
    //  * @param Request $request
    //  *
    //  * @return Response
    //  */
    // public function search(Request $request)
    // {
    //     $input = $request->all();

    //     $result = $this->repository->search($input);

    //     return view('media-manager::admin.files.index')
    //         ->with('files', $result[0]->get())
    //         ->with('pagination', $result[2])
    //         ->with('term', $result[1]);
    // }

    // /**
    //  * Show the form for creating a new Files.
    //  *
    //  * @return Response
    //  */
    // public function create()
    // {
    //     return view('media-manager::admin.files.create');
    // }

    // /**
    //  * Store a newly created Files in storage.
    //  *
    //  * @param FileRequest $request
    //  *
    //  * @return Response
    //  */
    // public function store(Request $request)
    // {
    //     $validation = $this->validation->check(File::$rules);

    //     if (!$validation['errors']) {
    //         $file = $this->repository->store($request->all());
    //     } else {
    //         return $validation['redirect'];
    //     }

    //     Siravel::notification('File saved successfully.', 'success');

    //     return redirect(route('root.files.index'));
    // }


    // /**
    //  * Remove a file.
    //  *
    //  * @param string $id
    //  *
    //  * @return Response
    //  */
    // public function remove($id)
    // {
    //     try {
    //         Storage::delete($id);
    //         $response = $this->responseService->apiResponse('success', 'success!');
    //     } catch (Exception $e) {
    //         $response = $this->responseService->apiResponse('error', $e->getMessage());
    //     }

    //     return $response;
    // }

    // /**
    //  * Show the form for editing the specified Files.
    //  *
    //  * @param int $id
    //  *
    //  * @return Response
    //  */
    // public function edit(Request $request, $id)
    // {
    //     $files = $this->repository->find($id);

    //     if (empty($files)) {
    //         Siravel::notification('File not found', 'warning');

    //         return redirect(route('root.files.index'));
    //     }

    //     return view('media-manager::admin.files.edit')->with('files', $files);
    // }

    // /**
    //  * Update the specified Files in storage.
    //  *
    //  * @param int         $id
    //  * @param FileRequest $request
    //  *
    //  * @return Response
    //  */
    // public function update($id, FileRequest $request)
    // {
    //     $files = $this->repository->find($id);

    //     if (empty($files)) {
    //         Siravel::notification('File not found', 'warning');

    //         return redirect(route('root.files.index'));
    //     }

    //     $files = $this->repository->update($files, $request->all());

    //     Siravel::notification('File updated successfully.', 'success');

    //     return Redirect::back();
    // }

    // /**
    //  * Remove the specified Files from storage.
    //  *
    //  * @param int $id
    //  *
    //  * @return Response
    //  */
    // public function destroy(Request $request, $id)
    // {
    //     $files = $this->repository->find($id);

    //     if (empty($files)) {
    //         Siravel::notification('File not found', 'warning');

    //         return redirect(route('root.files.index'));
    //     }

    //     if (is_file(storage_path($files->location))) {
    //         Storage::delete($files->location);
    //     } else {
    //         Storage::disk(config('siravel.storage-location', 'local'))->delete($files->location);
    //     }

    //     $files->delete();

    //     Siravel::notification('File deleted successfully.', 'success');

    //     return redirect(route('root.files.index'));
    // }

    /**
     * Store a newly created Files in storage.
     *
     * @param FileRequest $request
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        $validation = $this->validation->check(
            [
            'location' => [],
            ]
        );

        if (!$validation['errors']) {
            $file = $request->file('location');
            $fileSaved = $this->fileService->saveFile($file, 'files/');
            $fileSaved['name'] = Crypto::encrypt($fileSaved['name']);
            $fileSaved['mime'] = $file->getClientMimeType();
            $fileSaved['size'] = $file->getClientSize();
            $response = $this->responseService->apiResponse('success', $fileSaved);
        } else {
            $response = $this->responseService->apiErrorResponse($validation['errors'], $validation['inputs']);
        }

        return $response;
    }

    /**
     * Display the specified Images.
     *
     * @return Response
     */
    public function apiList(Request $request)
    {
        if (config('siravel.api-key') != $request->header('siravel')) {
            return $this->responseService->apiResponse('error', []);
        }

        $files = $this->repository->apiPrepared();

        return $this->responseService->apiResponse('success', $files);
    }
}
