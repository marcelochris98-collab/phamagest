<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $entrepriseId = DB::table('entreprises')
            ->where('slug', 'phamagest')
            ->value('id');

        if ($entrepriseId === null) {
            throw new RuntimeException('The initial PharmaGest company must exist before scoping roles.');
        }

        $rolesHadTeamColumn = Schema::hasColumn('roles', 'team_id');

        if (! $rolesHadTeamColumn) {
            Schema::table('roles', function (Blueprint $table) {
                $table->unsignedBigInteger('team_id')->nullable()->after('id');
                $table->dropUnique('roles_name_guard_name_unique');
                $table->unique(['team_id', 'name', 'guard_name'], 'roles_team_name_guard_unique');
            });
        }

        foreach (['model_has_roles', 'model_has_permissions'] as $tableName) {
            if (Schema::hasColumn($tableName, 'team_id')) {
                continue;
            }

            $foreignKeyColumn = $tableName === 'model_has_roles' ? 'role_id' : 'permission_id';
            $indexName = $tableName.'_'.str_replace('_id', '', $foreignKeyColumn).'_index';

            Schema::table($tableName, function (Blueprint $table) use ($foreignKeyColumn, $indexName) {
                $table->unsignedBigInteger('team_id')->nullable();
                $table->index($foreignKeyColumn, $indexName);
                $table->dropPrimary();
            });
        }

        DB::table('roles')->whereNull('team_id')->update(['team_id' => $entrepriseId]);
        DB::table('model_has_roles')->whereNull('team_id')->update(['team_id' => $entrepriseId]);
        DB::table('model_has_permissions')->whereNull('team_id')->update(['team_id' => $entrepriseId]);

        foreach ([
            'model_has_roles' => 'role_id',
            'model_has_permissions' => 'permission_id',
        ] as $tableName => $foreignKeyColumn) {
            $primaryKey = collect(Schema::getIndexes($tableName))->firstWhere('primary', true);

            if ($primaryKey && in_array('team_id', $primaryKey['columns'], true)) {
                continue;
            }

            if ($primaryKey) {
                $indexName = $tableName.'_'.str_replace('_id', '', $foreignKeyColumn).'_index';

                if (! in_array($indexName, Schema::getIndexListing($tableName), true)) {
                    Schema::table($tableName, function (Blueprint $table) use ($foreignKeyColumn, $indexName) {
                        $table->index($foreignKeyColumn, $indexName);
                    });
                }

                Schema::table($tableName, function (Blueprint $table) use ($primaryKey) {
                    $table->dropPrimary($primaryKey['name']);
                });
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $foreignKeyColumn) {
                $table->unsignedBigInteger('team_id')->nullable(false)->change();
                $table->primary(
                    ['team_id', $foreignKeyColumn, 'model_id', 'model_type'],
                    $tableName.'_team_'.$foreignKeyColumn.'_model_type_primary'
                );
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('roles', 'team_id')) {
            return;
        }

        throw new RuntimeException('Company-scoped permission roles cannot be safely downgraded after use.');
    }
};
