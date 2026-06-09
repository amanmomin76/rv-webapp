<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note_text');
            $table->timestamps();

            $table->index(['lead_id', 'created_at'], 'lead_notes_lead_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
    }
};
