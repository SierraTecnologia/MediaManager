<?php

namespace MediaManager\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use MediaManager\MediaManagerProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MediaManagerProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('sitec.media-manager', [
            'enabled' => true,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->migrateDatabase();
    }

    protected function migrateDatabase(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('binaries')) {
            Schema::create('binaries', function (Blueprint $table) {
                $table->string('hash', 64)->primary();
                $table->string('type', 255)->nullable();
                $table->string('size', 255)->nullable();
                $table->string('tags')->nullable();
                $table->text('details')->nullable();
                $table->string('extension', 6)->nullable();
                $table->string('mime')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('files')) {
            Schema::create('files', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 255)->nullable();
                $table->string('description')->nullable();
                $table->string('url', 255)->nullable();
                $table->string('path', 255)->nullable();
                $table->string('type', 255)->nullable();
                $table->string('filename', 255)->nullable();
                $table->string('size', 255)->nullable();
                $table->string('last_modified', 255)->nullable();
                $table->string('location')->nullable();
                $table->string('tags')->nullable();
                $table->text('details')->nullable();
                $table->string('extension')->nullable();
                $table->string('mime')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('imagens')) {
            Schema::create('imagens', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('location')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('videos')) {
            Schema::create('videos', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 255)->nullable();
                $table->string('description')->nullable();
                $table->string('unique_hash')->nullable();
                $table->string('url')->nullable();
                $table->string('path')->nullable();
                $table->string('mime')->nullable();
                $table->string('filename')->nullable();
                $table->string('size')->nullable();
                $table->string('last_modified')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('playlists')) {
            Schema::create('playlists', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('description')->nullable();
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('description')->nullable();
                $table->unsignedInteger('playlist_id')->nullable();
                $table->timestamps();
            });
        }
    }
}
