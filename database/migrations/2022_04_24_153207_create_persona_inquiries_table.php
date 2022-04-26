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
        Schema::create('persona_inquiries', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("persona_id")->unique();
            $table->text('status');
            $table->foreignUuid("reference_id")->references("id")->on("users");
            $table->timestamp('created_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('decisioned_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('redacted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_inquiries');
    }
};
