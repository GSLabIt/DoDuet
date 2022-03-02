<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("owner_id")->references("id")->on("users");
            $table->longText('chain'); // encrypted
            $table->longText('private_key'); // encrypted
            $table->longText('public_key'); // encrypted
            $table->longText('seed'); // encrypted
            $table->longText('address'); // encrypted
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
        Schema::dropIfExists('wallets');
    }
}
