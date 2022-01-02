<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('total_prize');
            $table->double('first_prize_rate', 8, 4);
            $table->foreignUuid("first_place_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->double('second_prize_rate', 8, 4);
            $table->foreignUuid("second_place_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->double('third_prize_rate', 8, 4);
            $table->foreignUuid("third_place_id")->references("id")->on(env("COMMON_DATABASE") . ".users");
            $table->double('treasury_rate', 8, 4);
            $table->double('fee_rate', 8, 4);
            $table->double('burning_rate', 8, 4);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });

        // Creates nullable foreign keys
        Schema::disableForeignKeyConstraints();
        Schema::table("challenges", function (Blueprint $table) {
            $table->uuid("first_place_id")->nullable()->change();
            $table->uuid("second_place_id")->nullable()->change();
            $table->uuid("third_place_id")->nullable()->change();
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
        Schema::dropIfExists('challenges');
    }
}
