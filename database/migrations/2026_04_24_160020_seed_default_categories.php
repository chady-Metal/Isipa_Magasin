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

        $categories = [
            ['nom' => 'Ordinateurs', 'description' => 'PC portables et desktop', 'image' => null],
            ['nom' => 'Accessoires', 'description' => 'Claviers, souris, casques et autres', 'image' => null],
            ['nom' => 'Reseau', 'description' => 'Routeurs, switches et materiel reseau', 'image' => null],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['nom' => $category['nom']],
                [
                    'description' => $category['description'],
                    'image' => $category['image'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Conserve les categories pour ne pas impacter les donnees produits.
    }
};
