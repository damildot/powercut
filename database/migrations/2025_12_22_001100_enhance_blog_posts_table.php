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
        Schema::table('blog_posts', function (Blueprint $table) {
            // Add category relationship
            $table->foreignId('blog_category_id')->nullable()->after('id')->constrained('blog_categories')->nullOnDelete();
            
            // Add author relationship
            $table->foreignId('user_id')->nullable()->after('blog_category_id')->constrained('users')->nullOnDelete();
            
            // Add excerpt fields (short description for listing pages)
            $table->text('excerpt_tr')->nullable()->after('title_en');
            $table->text('excerpt_en')->nullable()->after('excerpt_tr');
            
            // Add featured image alt text for SEO
            $table->string('image_alt_tr')->nullable()->after('image');
            $table->string('image_alt_en')->nullable()->after('image_alt_tr');
            
            // Add tags (JSON array)
            $table->json('tags')->nullable()->after('content_en');
            
            // Add reading time (calculated automatically)
            $table->integer('reading_time')->nullable()->after('tags')->comment('Reading time in minutes');
            
            // Add view counter for analytics
            $table->unsignedBigInteger('views_count')->default(0)->after('reading_time');
            
            // Add featured flag for homepage
            $table->boolean('is_featured')->default(false)->after('is_active');
            
            // Add sort order
            $table->integer('sort_order')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['blog_category_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'blog_category_id',
                'user_id',
                'excerpt_tr',
                'excerpt_en',
                'image_alt_tr',
                'image_alt_en',
                'tags',
                'reading_time',
                'views_count',
                'is_featured',
                'sort_order'
            ]);
        });
    }
};

