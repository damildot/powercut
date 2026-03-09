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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            
            // Multi-language fields
            $table->string('name_tr');
            $table->string('name_en')->nullable();
            $table->string('slug_tr')->unique();
            $table->string('slug_en')->nullable()->unique();
            $table->text('description_tr')->nullable();
            $table->text('description_en')->nullable();
            
            // SEO Fields
            $table->string('seo_title_tr')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->text('seo_description_tr')->nullable();
            $table->text('seo_description_en')->nullable();
            
            // Status & Ordering
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};

