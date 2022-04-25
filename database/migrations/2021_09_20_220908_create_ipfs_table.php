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
        Schema::create('ipfs', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('cid'); // encrypted
            $table->boolean('encrypted')->default(true);
            $table->longText('encryption_key'); // encrypted
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
        Schema::dropIfExists('ipfs');
    }
};
