<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained('materiels')->cascadeOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->decimal('cout', 10, 0)->nullable();
            $table->date('date_maintenance');
            $table->enum('statut', ['planifiee', 'en_cours', 'terminee'])->default('planifiee');
            $table->foreignId('enregistre_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
