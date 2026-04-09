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
        // Remplir les handles manquants
        \App\Models\User::all()->each(function ($user) {
            if (empty($user->handle)) {
                $baseHandle = \Illuminate\Support\Str::slug($user->name, '');
                if (empty($baseHandle)) {
                    $baseHandle = 'user' . $user->id;
                }
                $handle = $baseHandle;
                $counter = 1;

                while (\App\Models\User::where('handle', $handle)->exists()) {
                    $handle = $baseHandle.$counter;
                    $counter++;
                }

                $user->update(['handle' => strtolower($handle)]);
            }
        });

        // Rendre la colonne obligatoire
        Schema::table('users', function (Blueprint $table) {
            $table->string('handle')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('handle')->nullable()->change();
        });
    }
};
