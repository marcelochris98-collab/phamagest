<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('dci')->nullable();
            $table->string('forme', 100)->nullable();
            $table->string('dosage', 100)->nullable();
            $table->unsignedInteger('quantite_stock')->default(0);
            $table->unsignedInteger('seuil_alerte')->default(10);
            $table->date('date_peremption')->nullable();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->nullOnDelete();
            $table->timestamps();

            $table->index(['quantite_stock', 'seuil_alerte']);
            $table->index('date_peremption');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};
