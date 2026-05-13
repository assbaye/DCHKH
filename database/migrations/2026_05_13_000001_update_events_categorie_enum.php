<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE events MODIFY categorie ENUM('Journees Khassaides','Dahira','Ziar annuelle','Reunion') NOT NULL DEFAULT 'Reunion'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE events MODIFY categorie ENUM('Magal','Reunion','Collecte','Khassaides','Autre') NOT NULL DEFAULT 'Autre'");
    }
};
