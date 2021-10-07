<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_socials', function (Blueprint $table) {
            $table->foreignUuid('settings_id')->references('id')->on('user_settings');
            $table->foreignUuid('socials_id')->references('id')->on('socials');

            $table->primary(["settings_id","socials_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_socials');
    }
}
