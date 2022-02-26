<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection("common")->create('settings', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->longText("name"); // encrypted
            $table->longText("type"); // encrypted
            $table->longText("allowed_values")->nullable(); // encrypted
            $table->boolean("has_default_value")->default(false);
            $table->longText("default_value")->nullable(); // encrypted

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection("common")->dropIfExists('settings');
    }
}
