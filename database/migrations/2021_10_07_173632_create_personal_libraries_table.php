<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_libraries', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("owner_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->text('description');
            $table->string('name');
            $table->boolean('is_public')->default(false);
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
        Schema::dropIfExists('personal_libraries');
    }
}
