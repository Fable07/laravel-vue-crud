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
        Schema::table('products', function (Blueprint $table) {
            // Speeds up all product queries (every query filters by user_id)
            $table->index(['user_id', 'deleted_at']);
            // Speeds up search queries
            $table->index('name');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'deleted_at']);
            $table->dropIndex(['name']);
            $table->dropIndex(['category']);
        });
    }
};
