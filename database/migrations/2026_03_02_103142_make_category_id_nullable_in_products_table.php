<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NULL');
        } else {
            // SQLite: use raw SQL compatible with SQLite (recreate column via table copy)
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->change();
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NOT NULL');
        } else {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable(false)->change();
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }
};
