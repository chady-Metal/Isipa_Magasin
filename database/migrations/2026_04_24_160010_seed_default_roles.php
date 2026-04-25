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
        $now = now();

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Administrateur'],
            ['description' => 'Gestion des produits et administration.', 'updated_at' => $now, 'created_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Client'],
            ['description' => 'Achete des produits et gere ses commandes.', 'updated_at' => $now, 'created_at' => $now]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne supprime pas les roles pour eviter d'impacter les utilisateurs existants.
    }
};
