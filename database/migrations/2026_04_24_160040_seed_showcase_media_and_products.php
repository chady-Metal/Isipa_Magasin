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
            [
                'nom' => 'Ordinateurs',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Ordinateurs portables et desktop pour bureautique, developpement, design et usages professionnels.',
            ],
            [
                'nom' => 'Accessoires',
                'image' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Accessoires de qualite: claviers, souris, casques, webcams et equipements de confort.',
            ],
            [
                'nom' => 'Reseau',
                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Materiel reseau performant pour la maison, l entreprise et les salles informatiques.',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['nom' => $category['nom']],
                [
                    'description' => $category['description'],
                    'image' => $category['image'],
                    'updated_at' => $now,
                ]
            );
        }

        $categoriesMap = DB::table('categories')
            ->whereIn('nom', ['Ordinateurs', 'Accessoires', 'Reseau'])
            ->pluck('id', 'nom');

        $produits = [
            [
                'nom' => 'ISIPA ProBook 15',
                'prix' => 899.99,
                'stock' => 12,
                'description' => "Qualites: chassis aluminium, clavier retroeclaire, finition premium.\nPerformance: Intel Core i7 12e generation, ideal pour multitache et developpement.\nRAM: 16 Go DDR4.\nStockage: SSD NVMe 512 Go.\nEcran: 15.6\" Full HD IPS.\nAutonomie: jusqu a 10 heures.",
                'image' => 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-15',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'ISIPA UltraLite 14',
                'prix' => 1049.00,
                'stock' => 8,
                'description' => "Qualites: ultra fin, leger, design moderne pour la mobilite.\nPerformance: AMD Ryzen 7 7840U, execution fluide des applications lourdes.\nRAM: 16 Go LPDDR5.\nStockage: SSD 1 To.\nEcran: 14\" 2.2K.\nAutonomie: jusqu a 12 heures.",
                'image' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-10',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'WorkStation ISIPA X',
                'prix' => 1499.00,
                'stock' => 5,
                'description' => "Qualites: boitier robuste, refroidissement optimise, tres stable.\nPerformance: Intel Core i9 + GPU RTX 4060 pour montage video et rendu 3D.\nRAM: 32 Go DDR5.\nStockage: SSD 1 To + HDD 2 To.\nConnectique: USB-C, HDMI, DisplayPort, LAN Gigabit.",
                'image' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2025-12-20',
                'statut' => 'disponible',
                'categorie' => 'Ordinateurs',
            ],
            [
                'nom' => 'Clavier Mecanique ISIPA RGB',
                'prix' => 79.90,
                'stock' => 30,
                'description' => "Qualites: frappe precise, anti-ghosting, excellente durabilite.\nSwitches: mecaniques tactiles.\nRetroeclairage: RGB personnalisable.\nConfort: repose-poignet ergonomique inclus.",
                'image' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-03-04',
                'statut' => 'disponible',
                'categorie' => 'Accessoires',
            ],
            [
                'nom' => 'Souris Pro Sensor 16000 DPI',
                'prix' => 49.50,
                'stock' => 45,
                'description' => "Qualites: capteur precis, prise en main confortable, boutons programmables.\nPerformance: 16000 DPI ajustable.\nConnectivite: USB filaire faible latence.\nPoids: 78g optimise pour reactivite.",
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-02-18',
                'statut' => 'disponible',
                'categorie' => 'Accessoires',
            ],
            [
                'nom' => 'Routeur ISIPA WiFi 6 AX3000',
                'prix' => 119.00,
                'stock' => 20,
                'description' => "Qualites: signal stable, couverture etendue, installation simple.\nPerformance: debit jusqu a 3000 Mbps.\nNorme: WiFi 6.\nPorts: 4x LAN Gigabit + 1x WAN.\nSecurite: WPA3, controle parental, QoS.",
                'image' => 'https://images.unsplash.com/photo-1647427060118-4911c9821b82?auto=format&fit=crop&w=1200&q=80',
                'date_fabrication' => '2026-01-30',
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
            'ISIPA ProBook 15',
            'ISIPA UltraLite 14',
            'WorkStation ISIPA X',
            'Clavier Mecanique ISIPA RGB',
            'Souris Pro Sensor 16000 DPI',
            'Routeur ISIPA WiFi 6 AX3000',
        ])->delete();
    }
};
