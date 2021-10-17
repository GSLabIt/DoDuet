<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection("common")->create('personal_informations', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("owner_id")->references("id")->on(env("COMMON_DATABASE") . ".users")->cascadeOnDelete();
            $table->string("alias")->nullable();
            $table->string("mobile")->nullable();
            $table->text("profile_cover_path")->nullable();
            $table->longText("description")->nullable();
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
        Schema::connection("common")->dropIfExists('personal_informations');
    }
}
