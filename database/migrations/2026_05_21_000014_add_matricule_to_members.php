<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Member;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('matricule', 20)->nullable()->unique()->after('id');
        });

        // Générer les matricules pour les membres existants
        Member::orderBy('id')->each(function (Member $member, int $index) {
            $member->updateQuietly([
                'matricule' => 'DCHKH-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('matricule');
        });
    }
};
