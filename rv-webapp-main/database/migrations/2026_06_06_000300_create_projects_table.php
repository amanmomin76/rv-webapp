<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_code')->unique();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('project_name');
            $table->string('customer_name')->index();
            $table->string('source', 64)->nullable();
            $table->string('po_status', 64)->default('po_expected');
            $table->string('project_status', 64)->default('design');
            $table->decimal('value_amount', 14, 2)->nullable();
            $table->string('currency_code', 3)->default('INR');
            $table->date('delivery_date')->nullable()->index();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamps();

            $table->index(['source', 'po_status', 'project_status'], 'projects_source_po_status_idx');
            $table->index(['project_status', 'delivery_date'], 'projects_status_delivery_idx');
            $table->index(['owner_id', 'project_status', 'delivery_date'], 'projects_owner_status_delivery_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
