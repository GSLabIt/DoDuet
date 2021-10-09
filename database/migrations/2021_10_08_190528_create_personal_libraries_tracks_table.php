<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalLibrariesTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_libraries_tracks', function (Blueprint $table) {
            $table->foreignUuid("library_id")->references("id")->on("personal_libraries");
            $table->foreignUuid("track_id")->references("id")->on("tracks");

            $table->primary(["library_id","track_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_libraries_tracks_migration');
    }
}
