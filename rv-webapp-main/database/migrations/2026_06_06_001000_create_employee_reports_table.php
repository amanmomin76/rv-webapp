<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('report_date')->index();
            $table->time('work_started_at')->nullable();
            $table->time('work_ended_at')->nullable();
            $table->unsignedInteger('leads_contacted')->default(0);
            $table->unsignedInteger('follow_ups_completed')->default(0);
            $table->unsignedInteger('notes_added')->default(0);
            $table->unsignedInteger('quotations_shared')->default(0);
            $table->unsignedInteger('calls_made')->default(0);
            $table->unsignedInteger('whatsapp_messages')->default(0);
            $table->unsignedInteger('emails_sent')->default(0);
            $table->text('summary')->nullable();
            $table->text('issues')->nullable();
            $table->text('tomorrow_plan')->nullable();
            $table->string('status', 64)->default('Submitted')->index();
            $table->text('manager_feedback')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'report_date']);
            $table->index(['manager_id', 'report_date']);
            $table->index(['status', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_reports');
    }
};
