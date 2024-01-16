<?php

namespace MediaManager\Http\Controllers\Master;

use MediaManager\Models\Video;
use Pedreiro\CrudController;

class VideoController extends Controller
{
    use CrudController;

    public function __construct(Video $model)
    {
        $this->model = $model;
        parent::__construct();
    }
}
