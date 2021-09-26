<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSegmentsFunctionalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_segments_functionalities', function (Blueprint $table) {
            $table->foreignUuid("segment_id")->references("id")->on("user_segments");
            $table->foreignUuid("functionality_id")->references("id")->on("functionalities");
            $table->boolean("is_active")->default(false);

            $table->primary(["segment_id","functionality_id"]);
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
}
