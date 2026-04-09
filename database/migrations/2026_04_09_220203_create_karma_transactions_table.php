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
        Schema::create('karma_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('type'); // vote, interaction, bonus, reward
            $table->string('description')->nullable();

            // Source polymorphique (Post, Review, Comment)
            $table->nullableMorphs('source');

            // Metadata (ex: { 'lens': 'security' })
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Table pour traquer l'expertise par Lens
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('lens'); // Logic, Security, Performance, Clean Code
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'lens']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('karma_transactions');
    }
};
