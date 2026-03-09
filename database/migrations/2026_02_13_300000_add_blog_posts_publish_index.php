<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            try {
                $table->index(['is_active', 'published_at']);
            } catch (\Exception $e) {
                // index already exists
            }
        });
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            try {
                $table->dropIndex(['is_active', 'published_at']);
            } catch (\Exception $e) {
            }
        });
    }
};
