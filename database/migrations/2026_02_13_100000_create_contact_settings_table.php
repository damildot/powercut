<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_settings')) {
            return;
        }

        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('whatsapp_default_message_tr')->nullable();
            $table->text('whatsapp_default_message_en')->nullable();
            $table->string('seo_title_tr')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description_tr')->nullable();
            $table->string('seo_description_en')->nullable();
            $table->text('address_tr')->nullable();
            $table->text('address_en')->nullable();
            $table->string('working_hours_tr')->nullable();
            $table->string('working_hours_en')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
