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
        Schema::table('khassaides', function (Blueprint $table) {
            $table->dropColumn(['paroles', 'langue']);
        });
    }

    public function down(): void
    {
        Schema::table('khassaides', function (Blueprint $table) {
            $table->text('paroles')->nullable()->after('description');
            $table->enum('langue', ['wolof', 'arabe', 'français', 'autre'])->default('arabe')->after('paroles');
        });
    }
};
