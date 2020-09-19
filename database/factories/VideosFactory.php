<?php

use Faker\Generator as Faker;
use MediaManager\Models\Video;

$factory->define(MediaManager\Models\Video::class, function (Faker $faker) {

    $mediaService = new \MediaManager\Services\MediaService();
    $files = $mediaService->allFiles();
    $randomFile = array_rand($files, 1);

    return [
        'name' => $files[$randomFile]['name'],
        'url' => $files[$randomFile]['path'],
        'path' => $files[$randomFile]['relative_path'],
        'type' => $files[$randomFile]['type'],
        'filename' => $files[$randomFile]['filename'],
        'size' => $files[$randomFile]['size'],
        'last_modified' => $files[$randomFile]['last_modified'],
    ];
});