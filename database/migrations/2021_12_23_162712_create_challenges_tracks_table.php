<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges_tracks', function (Blueprint $table) {
            $table->foreignUuid("tracks_id");
            $table->foreignId("challenges_id");

            $table->primary(["tracks_id", "challenges_id"], "challenges_tracks_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenges_tracks');
    }
}
