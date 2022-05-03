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
        Schema::create('tracks', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text('name');
            $table->longText('description');
            $table->string('duration');
            $table->string('nft_id');
            $table->foreignUuid("owner_id")->references("id")->on("users");
            $table->foreignUuid("creator_id")->references("id")->on("users");
            $table->foreignUuid('ipfs_id')->references("id")->on("ipfs");
            $table->foreignUuid('cover_id')->references("id")->on("covers");
            $table->foreignUuid('lyric_id')->references("id")->on("lyrics");
            $table->foreignUuid('album_id')->references("id")->on("albums");
            $table->timestamps();
        });

        // Creates nullable foreign keys
        Schema::disableForeignKeyConstraints();
        Schema::table("tracks", function (Blueprint $table) {
            $table->uuid("cover_id")->nullable()->change();
            $table->uuid("lyric_id")->nullable()->change();
            $table->uuid("album_id")->nullable()->change();
        });
        Schema::enableForeignKeyConstraints();
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
};
