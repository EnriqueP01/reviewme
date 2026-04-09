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
        Schema::table('inline_suggestions', function (Blueprint $table) {
            $table->integer('end_line_number')->after('line_number')->nullable();
        });

        // Ensure post_comments and full_reviews are ready for reactions
        // We use polymorphic reactions, so no schema change needed here if already setup.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inline_suggestions', function (Blueprint $table) {
            $table->dropColumn('end_line_number');
        });
    }
};
