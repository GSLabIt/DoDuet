<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("first_track_id")->nullable()->references("id")->on("tracks");
            $table->foreignUuid("second_track_id")->nullable()->references("id")->on("tracks");
            $table->foreignUuid("third_track_id")->nullable()->references("id")->on("tracks");
            $table->unsignedBigInteger("first_prize")->nullable();
            $table->unsignedBigInteger("second_prize")->nullable();
            $table->unsignedBigInteger("third_prize")->nullable();
            $table->unsignedBigInteger("burned")->nullable();
            $table->unsignedBigInteger("fee")->nullable();
            $table->unsignedBigInteger("liquidity_pool")->nullable();
            $table->string("contract")->nullable();
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
        Schema::dropIfExists('elections');
    }
}
