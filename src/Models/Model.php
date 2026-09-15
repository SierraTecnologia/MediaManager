<?php

namespace MediaManager\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Polymorphic one to many class that stores images for any model.
 */
abstract class Model extends EloquentModel
{
}
