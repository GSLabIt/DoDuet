<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->bigInteger("id", unsigned: true)->primary();
            $table->string('total_prize');
            $table->double('first_prize_rate', 8, 4);
            $table->foreignUuid("first_place_id")->references("id")->on("users");
            $table->double('second_prize_rate', 8, 4);
            $table->foreignUuid("second_place_id")->references("id")->on("users");
            $table->double('third_prize_rate', 8, 4);
            $table->foreignUuid("third_place_id")->references("id")->on("users");
            $table->double('treasury_rate', 8, 4);
            $table->double('fee_rate', 8, 4);
            $table->double('burning_rate', 8, 4);
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
        Schema::dropIfExists('elections');
    }
}
