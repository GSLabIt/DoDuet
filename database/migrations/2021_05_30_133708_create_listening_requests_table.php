<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListeningRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listening_requests', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("election_id")->references("id")->on("elections");
            $table->foreignUuid("listener_id")->references("id")->on("users");
            $table->foreignUuid("track_id")->references("id")->on("tracks");
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
        Schema::dropIfExists('listening_requests');
    }
}
