<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpinionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opinions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("sender_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreignUuid("track_id")->references("id")->on("tracks")->cascadeOnDelete();
            $table->unsignedFloat("vote", 3, 1);
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
        Schema::dropIfExists('opinions');
    }
}
