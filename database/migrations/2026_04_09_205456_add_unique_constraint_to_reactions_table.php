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
        Schema::table('reactions', function (Blueprint $table) {
            // Un utilisateur ne peut avoir qu'une seule réaction par ressource (Unique Constraint)
            $table->unique(['user_id', 'reactable_id', 'reactable_type'], 'user_reaction_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropUnique('user_reaction_unique');
        });
    }
};
