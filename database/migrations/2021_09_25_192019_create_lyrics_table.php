<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lyrics', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text('name');
            $table->longText('lyric');
            $table->foreignUuid("owner_id")->references("id")->on("users");
            $table->foreignUuid("creator_id")->references("id")->on("users");
            $table->string('nft_id')->nullable();
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
        Schema::dropIfExists('lyrics');
    }
}
