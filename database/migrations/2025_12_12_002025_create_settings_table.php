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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable(); // Tarayıcı başlığı
            // 1. Logolar (Light ve Dark)
            $table->string('logo_light')->nullable(); // Beyaz zemin için
            $table->string('logo_dark')->nullable();  // Koyu zemin/Transparent için
            $table->string('favicon')->nullable();    // Favicon
            
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_phone')->nullable();

            $table->text('address')->nullable();
            $table->text('google_maps_embed')->nullable(); // İletişim sayfası haritası
            
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            // 3. SEO (Anasayfa İçin)
        $table->string('seo_title_tr')->nullable();
        $table->string('seo_title_en')->nullable();
        $table->text('seo_description_tr')->nullable();
        $table->text('seo_description_en')->nullable();
            
            $table->text('footer_text_tr')->nullable();
            $table->text('footer_text_en')->nullable();

            // 4. Anasayfa Slider (Repeater ile yönetilecek)
        // JSON formatında tutacağız: [{image, video, title_tr, title_en...}]
        $table->json('hero_slides')->nullable();
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
