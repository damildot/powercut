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
        Schema::create('product_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    
            $table->string('title');                 // "E-Catalog (TR)", "Brochure"
            $table->string('file_path');             // storage/products/docs/...
            $table->string('type')->nullable();      // ecatalog, brochure, manual vs.
            $table->string('language_code', 5)->nullable(); // 'tr', 'en' (istersen kullanırsın)
            $table->unsignedInteger('sort_order')->default(0);
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_documents');
    }
};
