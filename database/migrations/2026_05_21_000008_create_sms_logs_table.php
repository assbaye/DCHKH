<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'reunion' ou 'cotisation' ou 'autre'
            $table->text('message');
            $table->integer('nb_destinataires');
            $table->integer('nb_succes')->default(0);
            $table->integer('nb_echecs')->default(0);
            $table->foreignId('envoye_par')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reunion_id')->nullable()->constrained('reunions')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
