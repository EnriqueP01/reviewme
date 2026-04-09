<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajout de l'identifiant unique (handle)
            $table->string('handle')->nullable()->unique()->after('id');

            // On retire l'unicité du pseudo (name)
            // Utilisation d'un try catch pour éviter les erreurs si l'index a un nom différent
            try {
                $table->dropUnique(['name']);
            } catch (Exception $e) {
                // Fallback si l'index n'existe pas ou a un nom différent
            }
        });

        // Optionnel : Générer des handles pour les utilisateurs existants
        User::all()->each(function ($user) {
            if (empty($user->handle)) {
                $baseHandle = Str::slug($user->name, '');
                $handle = $baseHandle;
                $counter = 1;

                while (User::where('handle', $handle)->exists()) {
                    $handle = $baseHandle.$counter;
                    $counter++;
                }

                $user->update(['handle' => $handle]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('handle');
            $table->string('name')->unique()->change();
        });
    }
};
