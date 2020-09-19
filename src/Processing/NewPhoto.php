<?php

namespace MediaManager\Processing;

use App\Models\User;
use Log;
use MediaManager\Support\Trabalhando\Picture;

class NewPhoto
{
    
    public function __construct()
    {
        
    }

    public function actions()
    {
        $facedetect = new FaceDetector();
        $facedetect->faceDetect($_FILES['image']['tmp_name']);
        // $json = $facedetect->toJson();
        // echo $json;
        $facedetect->toJpeg();
    }

}
