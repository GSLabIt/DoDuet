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
            $table->uuid("id")->primary();
            $table::encrypted($table, "name");
            $table::encrypted($table, "type");
            $table::encrypted($table, "allowed_values", true, "[]");
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
