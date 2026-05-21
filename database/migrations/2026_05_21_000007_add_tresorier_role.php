<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE members MODIFY role ENUM('membre','admin','secretaire','tresorier') NOT NULL DEFAULT 'membre'");
    }

    public function down(): void
    {
        DB::table('members')->where('role', 'tresorier')->update(['role' => 'membre']);
        DB::statement("ALTER TABLE members MODIFY role ENUM('membre','admin','secretaire') NOT NULL DEFAULT 'membre'");
    }
};
