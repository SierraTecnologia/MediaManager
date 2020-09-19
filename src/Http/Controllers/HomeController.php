<?php

namespace MediaManager\Http\Controllers;

use MediaManager\Services\MediaManagerService;
use Illuminate\Support\Facades\Schema;
use Telefonica\Repositories\PersonRepository;
use MediaManager\Models\Media;

class HomeController extends BaseController
{
    protected $service;

    public function __construct(MediaManagerService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function index()
    {

        // dd($results);
        return view(
            'media-manager::media-manager.home'
            // compact('results')
        );
    }

    public function medias()
    {

        $results = Media::all();

        // dd($results);
        return view(
            'media-manager::components.gallery',
            compact('results')
        );
    }
    public function persons(PersonRepository $personRepo)
    {
        // $orders = $personRepo->getByCustomer(auth()->id())->orderBy('created_at', 'DESC')->paginate(\Illuminate\Support\Facades\Config::get('cms.pagination'));
        $persons = $personRepo->all(); //->paginate(\Illuminate\Support\Facades\Config::get('cms.pagination'));

        return view('media-manager::media-manager.persons')->with('persons', $persons);
    }
}
