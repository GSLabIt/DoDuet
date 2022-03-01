<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referreds', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("referrer_id")
                ->references("id")
                ->on(
                    config("referral.is_multi_db.active") ?
                        config("referral.is_multi_db.common_connection") . ".users" :
                        "users"
                );
            $table->foreignUuid("referred_id")
                ->references("id")
                ->on(
                    config("referral.is_multi_db.active") ?
                        config("referral.is_multi_db.connection") . ".users" :
                        "users"
                );
            $table->boolean("is_redeemed")->default(false);
            $table->integer("prize");
            $table->timestamp("redeemed_at")->nullable();
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
        Schema::dropIfExists('referreds');
    }
}
