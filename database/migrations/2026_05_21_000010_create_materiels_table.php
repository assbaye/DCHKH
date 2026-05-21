<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('categorie'); // sono, mobilier, textile, véhicule, autre
            $table->unsignedInteger('quantite')->default(1);
            $table->unsignedInteger('quantite_disponible')->default(1);
            $table->enum('etat', ['bon', 'usage', 'hors_service'])->default('bon');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('valeur', 10, 0)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
