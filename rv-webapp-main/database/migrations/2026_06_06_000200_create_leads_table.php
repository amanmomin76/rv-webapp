<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code')->unique();
            $table->string('customer_name')->index();
            $table->string('customer_mobile', 32)->nullable();
            $table->string('customer_email')->nullable();
            $table->string('company_name')->nullable()->index();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->text('requirement_message')->nullable();
            $table->string('requirement_product')->nullable();
            $table->string('requirement_quantity')->nullable();
            $table->string('requirement_budget')->nullable();
            $table->string('requirement_delivery')->nullable();
            $table->string('requirement_category')->nullable();
            $table->string('source', 64)->nullable()->index();
            $table->string('status', 64)->default('new')->index();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('inquiry_at')->nullable()->index();
            $table->timestamps();

            $table->index(['assigned_to_user_id', 'status', 'inquiry_at'], 'leads_owner_status_inquiry_idx');
            $table->index(['source', 'status'], 'leads_source_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
