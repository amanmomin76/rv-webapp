<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('file_name');
            $table->string('file_type', 32)->nullable();
            $table->string('disk', 32)->default('public');
            $table->string('path');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'created_at'], 'lead_documents_lead_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_documents');
    }
};
