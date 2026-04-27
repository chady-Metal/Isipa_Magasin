<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal("montant", 10, 2);
            $table->date("date_paiement");
            $table->string("methode_paiement");
            $table->foreignId("commande_id")->constrained("commandes")->onDelete("cascade");    
            $table->string("numero_compte");
            $table->string("reference_transaction")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
