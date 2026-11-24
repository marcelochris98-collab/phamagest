<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('verification_status')->default('pending')->index();
            $table->string('verification_note')->nullable();
            $table->string('numero_enregistrement')->nullable();
            $table->string('numero_autorisation')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subscription_status')->default('pending')->index();
            $table->string('subscription_plan')->default('standard');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_platform_admin')->default(false)->index();
        });

        Schema::create('entreprise_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained('entreprises')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();
            $table->timestamps();

            $table->index(['entreprise_id', 'type', 'created_at']);
        });

        DB::table('entreprises')
            ->where('slug', 'phamagest')
            ->update([
                'verification_status' => 'approved',
                'subscription_status' => 'active',
                'verified_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprise_documents');

        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'verification_status',
                'verification_note',
                'numero_enregistrement',
                'numero_autorisation',
                'verified_at',
                'subscription_status',
                'subscription_plan',
                'trial_ends_at',
                'subscription_ends_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_platform_admin');
        });
    }
};
