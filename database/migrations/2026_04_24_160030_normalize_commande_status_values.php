<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('commandes')->where('statut', 'en attente')->update(['statut' => 'en_attente']);
        DB::table('commandes')->where('statut', 'confirmé')->update(['statut' => 'confirmee']);
        DB::table('commandes')->where('statut', 'confirme')->update(['statut' => 'confirmee']);
        DB::table('commandes')->where('statut', 'livrée')->update(['statut' => 'livree']);
        DB::table('commandes')->where('statut', 'rejeté')->update(['statut' => 'rejetee']);
        DB::table('commandes')->where('statut', 'annulée')->update(['statut' => 'annulee']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('commandes')->where('statut', 'en_attente')->update(['statut' => 'en attente']);
    }
};
