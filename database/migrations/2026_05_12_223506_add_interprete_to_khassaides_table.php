<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khassaides', function (Blueprint $table) {
            $table->string('interprete')->nullable()->after('auteur');
        });
    }

    public function down(): void
    {
        Schema::table('khassaides', function (Blueprint $table) {
            $table->dropColumn('interprete');
        });
    }
};
