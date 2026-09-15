<?php

namespace MediaManager\Tests;

use MediaManager\MediaManager;
use MediaManager\Services\MediaManagerService;
use MediaManager\Services\FileService;

class MediaManagerServiceTest extends TestCase
{
    public function test_media_manager_singleton(): void
    {
        $manager = app('media-manager');
        $this->assertInstanceOf(MediaManager::class, $manager);
    }

    public function test_file_service_binding(): void
    {
        $service = app('FileService');
        $this->assertInstanceOf(FileService::class, $service);
    }

    public function test_media_manager_service(): void
    {
        $service = app(MediaManagerService::class);
        $this->assertInstanceOf(MediaManagerService::class, $service);
    }
}
