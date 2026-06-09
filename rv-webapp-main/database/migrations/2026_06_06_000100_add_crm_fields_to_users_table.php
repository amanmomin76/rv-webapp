<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('sales')->after('password')->index();
            $table->string('employment_status')->default('active')->after('role')->index();
            $table->date('joined_at')->nullable()->after('employment_status');
            $table->unsignedTinyInteger('performance_percent')->nullable()->after('joined_at');
            $table->string('performance_label')->nullable()->after('performance_percent');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'employment_status',
                'joined_at',
                'performance_percent',
                'performance_label',
            ]);
        });
    }
};
