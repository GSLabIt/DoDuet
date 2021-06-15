<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text("name");
            $table->enum("visibility", ["public", "experimental", "invite-only"])->default("public");
            $table->longText("description")->nullable();
            $table->longText("lyric")->nullable();
            $table->text("daw")->nullable();
            $table->string("duration");                 // in minutes and secs xx:xx
            // Integer representation of the nft (nft id) represented as string as it is a uint256, kept unique on the backend
            $table->string("nft_id", 100)->nullable()->index();
            $table->timestamps();

            $table->foreignUuid("owner_id")->references("id")->on(env("SSO_DATABASE") . ".users");
            $table->foreignUuid("creator_id")->references("id")->on(env("SSO_DATABASE") . ".users");
            $table->foreignUuid("genre_id")->references("id")->on("genres");

            // m2m user
            // o2m opinions
            // m2m tags
            // o2m media (not to handle)
            // m2m invited-users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
}
