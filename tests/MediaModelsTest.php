<?php

namespace MediaManager\Tests;

use MediaManager\Models\Binary;
use MediaManager\Models\File;
use MediaManager\Models\Video;
use MediaManager\Models\Playlist;
use MediaManager\Models\Group;

class MediaModelsTest extends TestCase
{
    public function test_can_create_binary_and_file(): void
    {
        $binary = Binary::create([
            'hash' => 'hash_abc123',
            'type' => 'document',
            'size' => '1024',
            'mime' => 'application/pdf',
        ]);

        $this->assertNotNull($binary);
        $this->assertEquals('hash_abc123', $binary->hash);

        $file = File::create([
            'name' => 'Report 2026',
            'path' => 'docs/report.pdf',
            'filename' => 'report.pdf',
            'unique_hash' => 'hash_abc123',
        ]);

        $this->assertNotNull($file);
        $this->assertEquals('Report 2026', $file->name);
    }

    public function test_can_create_video(): void
    {
        $video = Video::create([
            'name' => 'Intro Video',
            'path' => 'videos/intro.mp4',
            'filename' => 'intro.mp4',
            'mime' => 'video/mp4',
        ]);

        $this->assertNotNull($video);
        $this->assertEquals('Intro Video', $video->name);
        $this->assertNotNull($video->id);
    }

    public function test_playlist_and_group_relationship(): void
    {
        $playlist = Playlist::create([
            'name' => 'Default Playlist',
            'description' => 'Main media rotation',
        ]);

        $this->assertNotNull($playlist);
        $this->assertEquals('Default Playlist', $playlist->name);

        $group = Group::create([
            'name' => 'Lobby Screens',
            'playlist_id' => $playlist->id,
        ]);

        $this->assertNotNull($group);
        $this->assertEquals($playlist->id, $group->playlist->id);
        $this->assertCount(1, $playlist->groups);
        $this->assertEquals($group->id, $playlist->groups->first()->id);
    }
}
