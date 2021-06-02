<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_requests', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("election_id")->references("id")->on("elections");
            $table->foreignUuid("voter_id")->references("id")->on("users");
            $table->foreignUuid("track_id")->references("id")->on("tracks");
            $table->boolean("confirmed")->default(false);
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
        Schema::dropIfExists('vote_requests');
    }
}
