<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE members MODIFY role ENUM('membre','moderateur','admin','secretaire') NOT NULL DEFAULT 'membre'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE members MODIFY role ENUM('membre','moderateur','admin') NOT NULL DEFAULT 'membre'");
    }
};
