<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text('name');
            $table->longText('description');
            $table->string('duration');
            $table->string('nft_id');
            $table->foreignUuid("owner_id")->references("id")->on("users");
            $table->foreignUuid("creator_id")->references("id")->on("users");
            $table->foreignUuid('skynet_id')->references("id")->on("skynets");
            $table->foreignUuid('cover_id')->references("id")->on("covers")->nullable();
            $table->foreignUuid('lyric_id')->references("id")->on("lyrics")->nullable();
            $table->foreignUuid('album_id')->nullable();
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
        Schema::dropIfExists('tracks');
    }
}
