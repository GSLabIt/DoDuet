<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformsFunctionalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platforms_functionalities', function (Blueprint $table) {
            $table->foreignUuid("functionality_id")->references("id")->on("functionalities");
            $table->foreignUuid("platform_id")->references("id")->on("platforms");

            $table->primary(["functionality_id","platform_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platforms_functionalities');
    }
}
