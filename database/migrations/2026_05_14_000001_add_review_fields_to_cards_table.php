<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->timestamp('due_at')->nullable()->after('back');
            $table->timestamp('last_reviewed_at')->nullable()->after('due_at');
            $table->unsignedInteger('review_count')->default(0)->after('last_reviewed_at');
            $table->unsignedInteger('interval_days')->default(0)->after('review_count');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn([
                'due_at',
                'last_reviewed_at',
                'review_count',
                'interval_days',
            ]);
        });
    }
};
