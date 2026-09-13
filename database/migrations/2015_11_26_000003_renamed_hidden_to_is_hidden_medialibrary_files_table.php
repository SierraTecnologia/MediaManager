<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenamedHiddenToIsHiddenMedialibraryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('medialibrary_files') && Schema::hasColumn('medialibrary_files', 'hidden')) {
            Schema::table('medialibrary_files', function (Blueprint $table) {
                $table->renameColumn('hidden', 'is_hidden');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('medialibrary_files') && Schema::hasColumn('medialibrary_files', 'is_hidden')) {
            Schema::table('medialibrary_files', function (Blueprint $table) {
                $table->renameColumn('is_hidden', 'hidden');
            });
        }
    }
}
