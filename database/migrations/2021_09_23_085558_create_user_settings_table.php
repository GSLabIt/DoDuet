<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection("common")->create('user_settings', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("owner_id")->references("id")->on(env("COMMON_DATABASE") . ".users")->cascadeOnDelete();
            $table->foreignUuid("settings_id")->references("id")->on("settings")->cascadeOnDelete();
            $table->longText("setting");

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
        Schema::connection("common")->dropIfExists('user_settings');
    }
}
