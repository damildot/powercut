<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_messages', 'full_name')) {
                $table->string('full_name')->after('id');
            }
            if (!Schema::hasColumn('contact_messages', 'email')) {
                $table->string('email')->after('full_name');
            }
            if (!Schema::hasColumn('contact_messages', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('contact_messages', 'subject')) {
                $table->string('subject')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('contact_messages', 'message')) {
                $table->text('message')->after('subject');
            }
            if (!Schema::hasColumn('contact_messages', 'locale')) {
                $table->string('locale', 5)->default('tr')->after('message');
            }
            if (!Schema::hasColumn('contact_messages', 'status')) {
                $table->string('status')->default('new')->after('locale');
            }
            if (!Schema::hasColumn('contact_messages', 'ip')) {
                $table->ipAddress('ip')->nullable()->after('status');
            }
            if (!Schema::hasColumn('contact_messages', 'user_agent')) {
                $table->string('user_agent', 512)->nullable()->after('ip');
            }
        });

        // Ensure index exists (best-effort, ignore if already exists)
        Schema::table('contact_messages', function (Blueprint $table) {
            try {
                $table->index(['status', 'created_at']);
            } catch (\Exception $e) {
                // index already exists or cannot be created; ignore
            }
        });
    }

    public function down(): void
    {
        // No destructive rollback to avoid data loss
    }
};
