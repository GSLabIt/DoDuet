<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("tester_id")->references("id")->on("users");
            $table->unsignedBigInteger("utilizations")->default(0);
            $table->tinyInteger("has_answered_questionnaire")->default(0);
            $table->foreignUuid("test_id")->references("id")->on("tests");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_results');
    }
}
