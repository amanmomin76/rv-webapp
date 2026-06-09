<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject');
            $table->text('notes')->nullable();
            $table->string('type', 64)->default('manual');
            $table->string('status', 64)->default('pending');
            $table->string('source_context', 64)->nullable();
            $table->timestamp('due_at')->index();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_at'], 'follow_ups_status_due_idx');
            $table->index(['assigned_to_user_id', 'status', 'due_at'], 'follow_ups_owner_status_due_idx');
            $table->index(['lead_id', 'due_at'], 'follow_ups_lead_due_idx');
            $table->index(['project_id', 'due_at'], 'follow_ups_project_due_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
