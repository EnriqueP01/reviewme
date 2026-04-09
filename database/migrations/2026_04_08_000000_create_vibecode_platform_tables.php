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
        // On modifie la table users existante pour ajouter les champs GitHub
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'github_id')) {
                $table->string('github_id')->nullable()->unique()->after('id');
                $table->string('avatar')->nullable()->after('name');
                $table->integer('reputation_score')->default(0)->after('avatar');
                $table->text('bio')->nullable()->after('reputation_score');
                // Password nullable pour OAuth
                $table->string('password')->nullable()->change();
            }
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('visibility', ['public', 'private', 'group'])->default('public');
            $table->timestamps();
        });

        Schema::create('snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number')->default(1);
            $table->text('code_content');
            $table->string('language')->default('php');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('snippet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('line_number')->nullable();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Polymorphisme pour réagir aux posts ou reviews si besoin
            $table->morphs('reactable');
            $table->string('type', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('snippets');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('groups');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['github_id', 'avatar', 'reputation_score', 'bio']);
            $table->string('password')->nullable(false)->change();
        });
    }
};
