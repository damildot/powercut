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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            
            // İçerik (TR/EN)
            $table->string('title_tr');
            $table->string('title_en')->nullable();
            $table->string('slug_tr')->unique();
            $table->string('slug_en')->nullable();
            $table->longText('content_tr')->nullable();
            $table->longText('content_en')->nullable();
            
            // Durum ve Yayın
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
    
            // SEO
            $table->string('seo_title_tr')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->text('seo_description_tr')->nullable();
            $table->text('seo_description_en')->nullable();
    
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
