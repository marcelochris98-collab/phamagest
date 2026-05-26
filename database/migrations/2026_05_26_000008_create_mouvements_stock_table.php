<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicament_id')->constrained('medicaments')->cascadeOnDelete();
            $table->enum('type', ['entree', 'sortie']);
            $table->unsignedInteger('quantite');
            $table->string('motif');
            $table->dateTime('date');
            $table->timestamps();

            $table->index(['medicament_id', 'date']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
