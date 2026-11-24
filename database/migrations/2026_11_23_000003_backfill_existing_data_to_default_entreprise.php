<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const BUSINESS_TABLES = [
        'fournisseurs',
        'medicaments',
        'clients',
        'commandes',
        'commande_items',
        'ventes',
        'vente_items',
        'mouvements_stock',
    ];

    public function up(): void
    {
        DB::transaction(function () {
            $now = now();
            $entrepriseId = DB::table('entreprises')
                ->where('slug', 'phamagest')
                ->value('id');

            if ($entrepriseId === null) {
                $entrepriseId = DB::table('entreprises')->insertGetId([
                    'nom' => 'PharmaGest',
                    'slug' => 'phamagest',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach (self::BUSINESS_TABLES as $table) {
                DB::table($table)
                    ->whereNull('entreprise_id')
                    ->update(['entreprise_id' => $entrepriseId]);
            }

            $users = DB::table('users')->select('id')->get();
            foreach ($users as $user) {
                DB::table('entreprise_user')->insertOrIgnore([
                    'entreprise_id' => $entrepriseId,
                    'user_id' => $user->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }

    public function down(): void
    {
        $entrepriseId = DB::table('entreprises')
            ->where('slug', 'phamagest')
            ->value('id');

        if ($entrepriseId === null) {
            return;
        }

        DB::transaction(function () use ($entrepriseId) {
            foreach (self::BUSINESS_TABLES as $table) {
                DB::table($table)
                    ->where('entreprise_id', $entrepriseId)
                    ->update(['entreprise_id' => null]);
            }

            DB::table('entreprise_user')
                ->where('entreprise_id', $entrepriseId)
                ->delete();

            DB::table('entreprises')->where('id', $entrepriseId)->delete();
        });
    }
};
