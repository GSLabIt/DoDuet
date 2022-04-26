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
        Schema::create('persona_verifications', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("persona_id")->unique();
            $table->string("inquiry_id")->unique();
            $table->text('status');
            $table->timestamp('created_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('country_code')->nullable();
            $table->float('entity_confidence_score')->nullable();
            $table->float('document_similarity_score')->nullable();
            $table->string('entity_confidence_reasons');

            $table->json('photo_url')->nullable();

            $table->text('government_id_class');
            $table->text('capture_method')->nullable();

            $table->foreign("inquiry_id")->references("persona_id")->on("persona_inquiries");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_verifications');
    }
};
