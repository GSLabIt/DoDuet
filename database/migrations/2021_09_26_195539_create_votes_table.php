<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("voter_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->foreignUuid("track_id")->references("id")->on("tracks");
            $table->foreignId("election_id")->references("id")->on("elections");
            $table->unsignedSmallInteger("vote");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
