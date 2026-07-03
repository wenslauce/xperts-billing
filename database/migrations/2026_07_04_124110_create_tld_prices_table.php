<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tld_prices', function (Blueprint $table) {
            $table->id();
            $table->string('tld');
            $table->string('registrar');
            $table->decimal('register_price', 10, 2)->default(0);
            $table->decimal('renew_price', 10, 2)->default(0);
            $table->decimal('transfer_price', 10, 2)->default(0);
            $table->string('currency', 3)->default('KES');
            $table->timestamps();

            $table->unique(['tld', 'registrar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tld_prices');
    }
};