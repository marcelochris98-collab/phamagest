<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->decimal('total', 14, 2)->default(0);
            $table->enum('statut', ['brouillon', 'validee', 'annulee'])->default('brouillon');
            $table->dateTime('date_vente');
            $table->timestamps();

            $table->index('statut');
            $table->index('date_vente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
