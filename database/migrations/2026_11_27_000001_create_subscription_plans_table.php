<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('price_fcfa')->nullable();
            $table->unsignedInteger('user_limit')->nullable();
            $table->unsignedInteger('product_limit')->nullable();
            $table->boolean('kpis_enabled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('subscription_plans')->insert([
            [
                'slug' => 'essentiel',
                'name' => 'Essentiel',
                'price_fcfa' => null,
                'user_limit' => 2,
                'product_limit' => 500,
                'kpis_enabled' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'croissance',
                'name' => 'Croissance',
                'price_fcfa' => null,
                'user_limit' => 10,
                'product_limit' => 5000,
                'kpis_enabled' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'entreprise',
                'name' => 'Entreprise',
                'price_fcfa' => null,
                'user_limit' => null,
                'product_limit' => null,
                'kpis_enabled' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('subscription_requested_plan')->nullable()->after('subscription_plan');
            $table->timestamp('subscription_requested_at')->nullable()->after('subscription_requested_plan');
        });

        DB::table('entreprises')
            ->where('subscription_plan', 'standard')
            ->update(['subscription_plan' => 'essentiel']);
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn(['subscription_requested_plan', 'subscription_requested_at']);
        });

        Schema::dropIfExists('subscription_plans');
    }
};
