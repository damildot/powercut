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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
    
            // İlişkiler
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    
            // --- DİL BAĞIMSIZ ALANLAR ---
            $table->string('sku')->nullable();              // Model Kodu (Örn: H-260M)
            $table->string('thumbnail')->nullable();        // Liste görseli
            $table->json('gallery')->nullable();            // EKLEME: Ürün detayındaki diğer resimler
            $table->string('brochure_file')->nullable();    // EKLEME: İndirilebilir PDF Katalog
            $table->string('video_url')->nullable();        // EKLEME: Youtube Embed Linki
            
            $table->boolean('is_featured')->default(false); // Vitrin
            $table->boolean('is_new')->default(false);      // Yeni etiketi
            $table->boolean('is_active')->default(true);    // Yayında mı
            $table->unsignedInteger('sort_order')->default(0);
    
            // --- TÜRKÇE ALANLAR ---
            $table->string('name_tr');
            $table->string('slug_tr')->unique();
            $table->string('subtitle_tr')->nullable();      // Örn: "Yarı Otomatik Şerit Testere"
            $table->text('short_description_tr')->nullable();
            $table->longText('description_tr')->nullable(); // Genel Pazarlama Metni
            
            // EKLEME: Teknik Özellikler Tablosu (JSON)
            // Filament'te Key-Value olarak yöneteceğiz.
            // Örn: {"Motor Gücü": "1.5 kW", "Ağırlık": "450 kg"}
            $table->json('specifications_tr')->nullable(); 

            $table->string('seo_title_tr')->nullable();
            $table->string('seo_description_tr')->nullable();
    
            // --- İNGİLİZCE ALANLAR ---
            $table->string('name_en')->nullable();
            $table->string('slug_en')->unique()->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('short_description_en')->nullable();
            $table->longText('description_en')->nullable();
            
            // EKLEME: Teknik Özellikler İngilizce
            // Örn: {"Motor Power": "1.5 kW", "Weight": "450 kg"}
            $table->json('specifications_en')->nullable();

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
        Schema::dropIfExists('products');
    }
};
