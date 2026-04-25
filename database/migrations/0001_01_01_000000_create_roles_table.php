<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string("nom")->unique();
            $table->text("description")->nullable();
            $table->timestamps();
        });

        DB::table('roles')->insert([
            [
                'nom' => 'Administrateur',
                'description' => 'Gestion des produits et administration.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Client',
                'description' => 'Achete des produits et gere ses commandes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
