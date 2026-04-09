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
        // Global Comments with Threads
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('post_comments')->cascadeOnDelete();
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        // Full Review System
        Schema::create('full_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->integer('score')->default(0);
            $table->timestamps();
        });

        // Modified snippets within a Full Review
        Schema::create('full_review_snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('full_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('snippet_id')->constrained()->cascadeOnDelete();
            $table->longText('modified_content');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Inline Suggestions (Micro-modifications)
        Schema::create('inline_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('snippet_id')->constrained()->cascadeOnDelete();
            $table->integer('line_number');
            $table->text('original_content');
            $table->text('suggested_content');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inline_suggestions');
        Schema::dropIfExists('full_review_snippets');
        Schema::dropIfExists('full_reviews');
        Schema::dropIfExists('post_comments');
    }
};
