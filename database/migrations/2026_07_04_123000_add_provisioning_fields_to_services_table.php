<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->timestamp('provisioned_at')->nullable()->after('next_due_date');
            $table->text('provisioning_errors')->nullable()->after('provisioned_at');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['provisioned_at', 'provisioning_errors']);
        });
    }
};