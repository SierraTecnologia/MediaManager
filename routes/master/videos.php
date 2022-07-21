
<?php
Route::resource('/videos', 'VideoController')->parameters([
  'videos' => 'id'
]);
