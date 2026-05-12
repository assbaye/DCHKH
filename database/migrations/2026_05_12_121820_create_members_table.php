<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone', 20)->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable()->default('Dielerlou Syll');
            $table->string('photo')->nullable();
            $table->date('date_adhesion')->default(now());
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->enum('role', ['membre', 'moderateur', 'admin'])->default('membre');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
