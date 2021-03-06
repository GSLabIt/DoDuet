<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->foreignUuid("voter_id")->references("id")->on("users");
            $table->foreignUuid("track_id")->references("id")->on("tracks");
            $table->foreignId("challenge_id")->nullable()->references("id")->on("challenges");
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
};
