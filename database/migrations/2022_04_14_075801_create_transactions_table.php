<?php

use Doinc\Wallet\Enums\TransactionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            if(config("wallet.uuid")) {
                $table->nullableUuidMorphs("from");
                $table->nullableUuidMorphs("to");
            }
            else {
                $table->nullableMorphs("from");
                $table->nullableMorphs("to");
            }

            $types = array_map(fn(TransactionType $type) => $type->value, TransactionType::cases());
            $table->enum('type', $types)->index();
            $table->string("amount")->default("0");
            $table->boolean("refunded")->default(false);
            $table->boolean("confirmed")->default(true);
            $table->timestamp("confirmed_at")->nullable();
            $table->longText("metadata")->nullable();
            $table->string("discount")->default("0");
            $table->string("fee")->default("0");

            $table->timestamps();

            $table->index(['from_wallet_id', 'to_wallet_id'], 'i_from_wallet');
            $table->index(['from_type', 'from_id'], 'iFrom_type_id');
            $table->index(['from_type', 'from_id', 'type'], 'iFrom_type');
            $table->index(['from_type', 'from_id', 'confirmed'], 'iFrom_confirmed');
            $table->index(['from_type', 'from_id', 'type', 'confirmed'], 'iFrom_type_confirmed');
            $table->index(['to_type', 'to_id'], 'iTo_type_id');
            $table->index(['to_type', 'to_id', 'type'], 'iTo_type');
            $table->index(['to_type', 'to_id', 'confirmed'], 'iTo_confirmed');
            $table->index(['to_type', 'to_id', 'type', 'confirmed'], 'iTo_type_confirmed');
            $table->index(['from_type', 'from_id', 'to_type', 'to_id'], 'iFromTo_type_id');
        });
    }
};
