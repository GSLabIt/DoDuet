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
        Schema::create('tests', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("functionality_id")->references("id")->on("functionalities");
            $table->foreignUuid("user_segment_id")->references("id")->on("user_segments");
            $table->foreignUuid("questionnaire_id")->references("id")->on("questionnaires");
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
        Schema::dropIfExists('tests');
    }
};
