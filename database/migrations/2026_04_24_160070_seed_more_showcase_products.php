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

        $categoriesMap = DB::table('categories')
            ->whereIn('nom', ['Ordinateurs', 'Accessoires', 'Reseau'])
            ->pluck('id', 'nom');

        $produits = [
            [
                'nom' => 'ISIPA StudentBook 14',
                'prix' => 699.00,
                'stock' => 25,
                'description' => "Qualites: leger et solide pour etudiants.\nPerformance: Intel Core i5 11e generation.\nRAM: 8 Go DDR4.\nStockage: SSD 256 Go.\nEcran: 14\" Full HD.\nAutonomie: environ 8 heures.",
                'image' => 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-09',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'ISIPA Creator 16',
                'prix' => 1799.00,
                'stock' => 6,
                'description' => "Qualites: ecran large pour creation et productivite.\nPerformance: Intel Core Ultra 9 + RTX 4070.\nRAM: 32 Go DDR5.\nStockage: SSD 1 To.\nEcran: 16\" QHD 165Hz.\nUsage: montage, rendu 3D, IA locale.",
                'image' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-02',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'Mini PC ISIPA Office',
                'prix' => 539.00,
                'stock' => 14,
                'description' => "Qualites: compact, silencieux, faible consommation.\nPerformance: Ryzen 5 5600U.\nRAM: 16 Go.\nStockage: SSD 512 Go.\nConnectique: HDMI dual, USB-C, WiFi 6.\nIdeal pour bureautique et ERP.",
                'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-15',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'Casque ISIPA Studio Sound',
                'prix' => 64.90,
                'stock' => 35,
                'description' => "Qualites: son clair, arceau confortable, isolation passive.\nPerformance audio: 20Hz-20kHz.\nConnectivite: Jack 3.5mm + adaptateur USB.\nIdeal pour cours, visio et multimedia.",
                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-21',
                'statut' => 'disponible',
                'categorie' => 'Accessoires',
            ],
            [
                'nom' => 'Webcam ISIPA Full HD',
                'prix' => 39.90,
                'stock' => 40,
                'description' => "Qualites: image nette, installation plug-and-play.\nResolution: 1080p 30fps.\nMicro: reduction de bruit integree.\nUsage: cours en ligne, reunions, streaming.",
                'image' => 'https://images.unsplash.com/photo-1623949556303-b0d17d198863?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-05',
                'statut' => 'disponible',
                'categorie' => 'Accessoires',
            ],
            [
                'nom' => 'SSD Externe ISIPA 1 To',
                'prix' => 109.00,
                'stock' => 18,
                'description' => "Qualites: robuste, compact, transfert rapide.\nPerformance: jusqu a 1050 MB/s.\nCapacite: 1 To.\nConnectique: USB-C 3.2.\nIdeal pour sauvegarde et mobilite.",
                'image' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-28',
                'statut' => 'disponible',
                'categorie' => 'Accessoires',
            ],
            [
                'nom' => 'Switch ISIPA Smart 24 Ports',
                'prix' => 229.00,
                'stock' => 9,
                'description' => "Qualites: administration simple, haute stabilite.\nPerformance: 24 ports Gigabit.\nFonctions: VLAN, QoS, monitoring.\nUsage: laboratoires, PME, salles reseau.",
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-22',
                'statut' => 'disponible',
                'categorie' => 'Reseau',
            ],
            [
                'nom' => 'Point d acces ISIPA Mesh',
                'prix' => 149.00,
                'stock' => 16,
                'description' => "Qualites: couverture uniforme, roaming intelligent.\nPerformance: WiFi 6 dual band.\nPortee: ideale pour grands espaces.\nSecurite: WPA3 + isolation invites.",
                'image' => 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-03-03',
                'statut' => 'disponible',
                'categorie' => 'Reseau',
            ],
            [
                'nom' => 'Onduleur ISIPA 1200VA',
                'prix' => 189.00,
                'stock' => 11,
                'description' => "Qualites: protection electrique fiable.\nPuissance: 1200VA.\nAutonomie: 10-20 minutes selon charge.\nFonctions: regulation tension, protection surtension.",
                'image' => 'https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-17',
                'statut' => 'disponible',
                'categorie' => 'Reseau',
            ],
        ];

        foreach ($produits as $produit) {
            $categorieId = $categoriesMap[$produit['categorie']] ?? null;
            if (! $categorieId) {
                continue;
            }

            DB::table('produits')->updateOrInsert(
                ['nom' => $produit['nom']],
                [
                    'prix' => $produit['prix'],
                    'stock' => $produit['stock'],
                    'description' => $produit['description'],
                    'image' => $produit['image'],
                    'date_fabrication' => $produit['date_fabrication'],
                    'statut' => $produit['statut'],
                    'categorie_id' => $categorieId,
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
        DB::table('produits')->whereIn('nom', [
            'ISIPA StudentBook 14',
            'ISIPA Creator 16',
            'Mini PC ISIPA Office',
            'Casque ISIPA Studio Sound',
            'Webcam ISIPA Full HD',
            'SSD Externe ISIPA 1 To',
            'Switch ISIPA Smart 24 Ports',
            'Point d acces ISIPA Mesh',
            'Onduleur ISIPA 1200VA',
        ])->delete();
    }
};
