<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text('name');
            $table->foreignUuid("owner_id")->references("id")->on("users");
            $table->foreignUuid("creator_id")->references("id")->on("users");
            $table->string('nft_id')->nullable();
            $table->foreignUuid("cover_id")->references("id")->on("covers");
            $table->longText('description');
            $table->timestamps();
        });

        // Creates nullable foreign keys
        Schema::disableForeignKeyConstraints();
        Schema::table("albums", function (Blueprint $table) {
            $table->uuid("cover_id")->nullable()->change();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
};
