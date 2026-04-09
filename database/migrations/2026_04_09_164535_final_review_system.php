<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('inline_suggestions');
        Schema::dropIfExists('full_review_snippets');
        Schema::dropIfExists('full_reviews');

        Schema::create('full_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();
        });

        Schema::create('full_review_snippets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('full_review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('snippet_id')->constrained()->cascadeOnDelete();
            $table->longText('modified_content')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('inline_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('snippet_id')->constrained()->cascadeOnDelete();
            $table->integer('line_number');
            $table->integer('end_line_number')->nullable();
            $table->longText('original_content')->nullable();
            $table->longText('suggested_content')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inline_suggestions');
        Schema::dropIfExists('full_review_snippets');
        Schema::dropIfExists('full_reviews');
    }
};
