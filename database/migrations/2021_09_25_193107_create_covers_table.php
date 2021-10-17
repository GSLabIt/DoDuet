<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text('name');
            $table->foreignUuid("skynet_id")->references("id")->on("skynets");
            $table->string('nft_id')->nullable();
            $table->foreignUuid("owner_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->foreignUuid("creator_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
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
        Schema::dropIfExists('covers');
    }
}
