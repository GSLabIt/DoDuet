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
            $table->foreignUuid('lyric_id')->nullable();
            $table->foreignUuid('cover_id')->nullable();
            $table->foreignUuid('skynet_id');
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
