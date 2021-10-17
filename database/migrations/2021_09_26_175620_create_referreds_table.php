<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referreds', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("referrer_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->foreignUuid("referred_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->boolean("is_redeemed")->default(false);
            $table->integer("prize");
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
        Schema::dropIfExists('referreds');
    }
}
