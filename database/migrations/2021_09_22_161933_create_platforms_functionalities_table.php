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
        Schema::connection("common")->create('functionalities_platforms', function (Blueprint $table) {
            $table->foreignUuid("functionalities_id");
            $table->foreignUuid("platforms_id");

            $table->primary(["functionalities_id","platforms_id"], "functionalities_platforms_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection("common")->dropIfExists('platforms_functionalities');
    }
}
