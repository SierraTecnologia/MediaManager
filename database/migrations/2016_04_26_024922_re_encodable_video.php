<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ReEncodableVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('encodings')) {
            Schema::table(
                'encodings', function (Blueprint $table) {
                    if (! Schema::hasColumn('encodings', 'response')) {
                        $table->text('response')->after('message')->nullable();
                    }
                    if (! Schema::hasColumn('encodings', 'preset')) {
                        $table->string('preset')->after('encodable_attribute')->default('');
                    }
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('encodings')) {
            Schema::table(
                'encodings', function (Blueprint $table) {
                    foreach (['preset', 'response'] as $column) {
                        if (Schema::hasColumn('encodings', $column)) {
                            $table->dropColumn($column);
                        }
                    }
                }
            );
        }
    }
}
