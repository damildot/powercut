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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Hizmet Başlığı (Örn: Teknik Servis)
            $table->string('slug')->unique(); // SEO URL (teknik-servis)
            $table->string('short_description')->nullable(); // Listeleme için kısa özet
            $table->longText('content')->nullable(); // Detaylı içerik
            $table->string('icon')->nullable(); // Hizmeti temsil eden ikon veya küçük resim
            $table->string('image')->nullable(); // Kapak resmi
            $table->string('seo_title')->nullable(); // SEO Başlığı
            $table->text('seo_description')->nullable(); // SEO Açıklaması
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // Sıralama (Hangi hizmet önce gözüksün)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
