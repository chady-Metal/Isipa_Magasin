<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('last_seen_at')->nullable()->after('last_login_at');
            $table->timestamp('deleted_at')->nullable()->after('last_seen_at');
            $table->string('last_operation')->nullable()->after('deleted_at');
            $table->text('deletion_reason')->nullable()->after('last_operation');
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('statut');
            $table->decimal('promotion_percentage', 5, 2)->nullable()->after('is_featured');
            $table->string('promotion_title')->nullable()->after('promotion_percentage');
            $table->text('promotion_description')->nullable()->after('promotion_title');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->string('tracking_code')->nullable()->after('rejection_reason');
            $table->foreignId('processed_by')->nullable()->after('tracking_code')->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable()->after('processed_by');
        });

        Schema::table('reclamations', function (Blueprint $table) {
            $table->text('admin_response')->nullable()->after('message');
            $table->foreignId('responded_by')->nullable()->after('admin_response')->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable()->after('responded_by');
        });

        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'produit_id']);
        });

        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained('produits')->nullOnDelete();
            $table->string('type')->default('produit');
            $table->unsignedTinyInteger('note');
            $table->text('commentaire');
            $table->timestamps();
        });

        Schema::create('historiques_recherche', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('terme_recherche');
            $table->string('categorie')->nullable();
            $table->unsignedInteger('resultats')->default(0);
            $table->timestamps();
        });

        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('action');
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'permission_id']);
        });

        $now = now();

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Super Administrateur'],
            ['description' => 'Controle global du systeme et gestion des autres administrateurs.', 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Administrateur'],
            ['description' => 'Gestion du catalogue, des commandes et des comptes clients.', 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Gerant'],
            ['description' => 'Suivi de stock, commandes et support client.', 'created_at' => $now, 'updated_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['nom' => 'Client'],
            ['description' => 'Achete des produits et gere ses commandes.', 'created_at' => $now, 'updated_at' => $now]
        );

        $permissions = [
            ['nom' => 'products.create', 'description' => 'Ajouter un produit'],
            ['nom' => 'products.update', 'description' => 'Mettre a jour un produit'],
            ['nom' => 'products.delete', 'description' => 'Supprimer un produit'],
            ['nom' => 'orders.view', 'description' => 'Voir les commandes clients'],
            ['nom' => 'orders.manage', 'description' => 'Confirmer ou rejeter une commande'],
            ['nom' => 'stock.view', 'description' => 'Suivre le stock'],
            ['nom' => 'stock.manage', 'description' => 'Gerer le stock et les alertes'],
            ['nom' => 'claims.reply', 'description' => 'Repondre aux reclamations'],
            ['nom' => 'customers.delete', 'description' => 'Supprimer un client'],
            ['nom' => 'promotions.manage', 'description' => 'Gerer les promotions'],
            ['nom' => 'sales.view', 'description' => 'Voir les etats de vente'],
            ['nom' => 'admins.create', 'description' => 'Creer un administrateur'],
            ['nom' => 'admins.revoke', 'description' => 'Revoquer un administrateur'],
            ['nom' => 'admins.permissions', 'description' => 'Attribuer les permissions'],
            ['nom' => 'admins.activity.view', 'description' => 'Suivre les activites des administrateurs'],
            ['nom' => 'admins.message', 'description' => 'Contacter d autres administrateurs'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['nom' => $permission['nom']],
                ['description' => $permission['description'], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $rolePermissions = [
            'Super Administrateur' => collect($permissions)->pluck('nom')->all(),
            'Administrateur' => [
                'products.create',
                'products.update',
                'products.delete',
                'orders.view',
                'stock.view',
                'stock.manage',
                'customers.delete',
                'admins.message',
                'promotions.manage',
                'sales.view',
            ],
            'Gerant' => [
                'orders.view',
                'orders.manage',
                'stock.view',
                'claims.reply',
                'admins.message',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $roleId = DB::table('roles')->where('nom', $roleName)->value('id');

            foreach ($permissionNames as $permissionName) {
                $permissionId = DB::table('permissions')->where('nom', $permissionName)->value('id');

                if ($roleId && $permissionId) {
                    DB::table('attributions')->updateOrInsert(
                        ['role_id' => $roleId, 'permissions_id' => $permissionId],
                        ['created_at' => $now, 'updated_at' => $now]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
        Schema::dropIfExists('admin_messages');
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('historiques_recherche');
        Schema::dropIfExists('avis');
        Schema::dropIfExists('favoris');

        Schema::table('reclamations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('responded_by');
            $table->dropColumn(['admin_response', 'responded_at']);
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('processed_by');
            $table->dropColumn(['tracking_code', 'processed_at']);
        });

        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'promotion_percentage', 'promotion_title', 'promotion_description']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_seen_at', 'deleted_at', 'last_operation', 'deletion_reason']);
        });
    }
};
