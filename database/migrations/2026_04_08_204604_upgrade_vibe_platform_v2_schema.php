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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('title');
            $table->text('review_goals')->nullable()->after('short_description');
            $table->text('improvement_goals')->nullable()->after('review_goals');
        });

        Schema::table('snippets', function (Blueprint $table) {
            $table->text('description')->nullable()->after('code_content');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('member'); // member, moderator
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_user');

        Schema::table('snippets', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'review_goals', 'improvement_goals']);
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
