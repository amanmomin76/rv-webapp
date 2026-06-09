<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('external_source_id')->nullable()->after('source');
            $table->json('source_payload')->nullable()->after('external_source_id');
            $table->foreignId('manager_user_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('imported_at')->nullable()->after('inquiry_at');

            $table->index(['manager_user_id', 'status', 'inquiry_at'], 'leads_manager_status_inquiry_idx');
            $table->index(['source', 'external_source_id'], 'leads_source_external_idx');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_manager_status_inquiry_idx');
            $table->dropIndex('leads_source_external_idx');
            $table->dropConstrainedForeignId('manager_user_id');
            $table->dropColumn([
                'external_source_id',
                'source_payload',
                'imported_at',
            ]);
        });
    }
};
