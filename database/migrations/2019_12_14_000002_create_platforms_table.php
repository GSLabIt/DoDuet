<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->longText('name'); // encrypted
            $table->longText("domain"); // encrypted
            $table->boolean("is_public")->default(false);
            $table->boolean("is_password_protected")->default(false);
            $table->text("password")->nullable(); // already hashed
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
        Schema::dropIfExists('platforms');
    }
}
