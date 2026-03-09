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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
    
            // Dil bağımsız alanlar
            $table->string('image')->nullable();          // kategori görseli (liste/hero için)
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_home')->default(false); // anasayfada gösterilsin mi?
            $table->unsignedInteger('sort_order')->default(0);
    
            // Türkçe alanlar
            $table->string('name_tr');
            $table->string('slug_tr')->unique();
            $table->string('subtitle_tr')->nullable();
            $table->text('description_tr')->nullable();
            $table->string('seo_title_tr')->nullable();
            $table->string('seo_description_tr')->nullable();
    
            // İngilizce alanlar
            $table->string('name_en')->nullable();
            $table->string('slug_en')->unique()->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('description_en')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description_en')->nullable();
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
