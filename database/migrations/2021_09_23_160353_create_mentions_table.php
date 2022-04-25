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
        Schema::create('mentions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("mentioner_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreignUuid("mentioned_id")->references("id")->on("users")->cascadeOnDelete();
            $table->uuidMorphs("mentionable");
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
        Schema::dropIfExists('mentions');
    }
};
