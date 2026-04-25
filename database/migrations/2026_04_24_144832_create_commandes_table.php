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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->date("date_commande");
            $table->string("statut")->default("en_attente");
            $table->text("rejection_reason")->nullable();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->string("adresse_livraison");
            $table->date("date_livraison");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
