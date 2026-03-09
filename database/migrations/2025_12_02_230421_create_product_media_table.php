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
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    
            $table->enum('media_type', ['image', 'video']); // image / video
            $table->string('path');                         // image path veya video URL
            $table->string('alt_text')->nullable();         // image için alt, video için title gibi
            $table->boolean('is_main')->default(false);     // ana görsel/video mi?
            $table->unsignedInteger('sort_order')->default(0); // slider sırası
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
