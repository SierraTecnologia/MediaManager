<?php

namespace MediaManager\Http\Controllers\Master;

// use MediaManager\Http\Controllers\Controller as BaseController;
use Pedreiro\CrudController;
use Pedreiro\Traits\TemplateControllerTrait;
use Pedreiro\Http\Controllers\Master\Controller as BaseController;


class Controller extends BaseController
{
  // use TemplateControllerTrait;
  // use CrudController;
  public function __construct()
  {
      parent::__construct();
  }
}
