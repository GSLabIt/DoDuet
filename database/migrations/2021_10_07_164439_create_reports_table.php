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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuidMorphs("reportable");
            $table->foreignUuid("reporter_id")->references("id")->on("users");
            $table->foreignUuid("reason_id")->references("id")->on("report_reasons");
            $table->longText('extra_informations');
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
        Schema::dropIfExists('reports');
    }
};
