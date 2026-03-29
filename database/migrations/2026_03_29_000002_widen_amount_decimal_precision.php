<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Widens DECIMAL(8,2) → DECIMAL(15,2) on transactions and obligations.
    // Safe for production: MySQL preserves all existing values when increasing
    // precision. No data is modified, only the column definition changes.
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
        });

        Schema::table('obligations', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->default(0)->change();
        });

        Schema::table('obligations', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->default(0)->change();
        });
    }
};
