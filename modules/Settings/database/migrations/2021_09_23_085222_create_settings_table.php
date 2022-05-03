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
        Schema::create('settings', function (Blueprint $table) {
            if(config("settings.uuid")) {
                $table->uuid("id")->primary();
            }
            else {
                $table->id();
            }

            $table::encrypted($table, "name");
            $table::encrypted($table, "type");
            $table->boolean("has_default_value")->default(false);
            $table::encrypted($table, "default_value", is_nullable: true);

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
        Schema::dropIfExists('settings');
    }
}
