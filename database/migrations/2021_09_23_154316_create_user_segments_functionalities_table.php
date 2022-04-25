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
        Schema::create('functionalities_user_segments', function (Blueprint $table) {
            $table->foreignUuid("user_segments_id");
            $table->foreignUuid("functionalities_id");
            $table->boolean("is_active")->default(false);

            $table->primary(["user_segments_id","functionalities_id"], "segments_functionalities_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_segments_functionalities');
    }
};
