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
        Schema::create('khassaides', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('auteur')->default('Cheikh Ahmadou Bamba');
            $table->text('description')->nullable();
            $table->text('paroles')->nullable();
            $table->string('fichier_audio')->nullable();
            $table->enum('langue', ['wolof', 'arabe', 'français', 'autre'])->default('arabe');
            $table->boolean('telechargeable')->default(true);
            $table->boolean('publie')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khassaides');
    }
};
