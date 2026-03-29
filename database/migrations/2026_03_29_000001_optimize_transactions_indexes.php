<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $existing = collect(Schema::getIndexes('transactions'))->pluck('name');

            // Low-cardinality standalone indexes on enum columns (2 values each).
            // MySQL never uses them — the optimizer always prefers a full scan or
            // the user_id FK index — but they slow down every INSERT/UPDATE.
            if ($existing->contains('transactions_type_index')) {
                $table->dropIndex('transactions_type_index');
            }
            if ($existing->contains('transactions_state_index')) {
                $table->dropIndex('transactions_state_index');
            }

            // Composite index covering the dominant query pattern across all components:
            // WHERE user_id = ? AND state = ? AND date >= ? AND date < ?
            // Replaces and outperforms the two individual indexes above.
            if (!$existing->contains('transactions_user_state_date_index')) {
                $table->index(['user_id', 'state', 'date'], 'transactions_user_state_date_index');
            }
        });

        Schema::table('obligations', function (Blueprint $table) {
            $existing = collect(Schema::getIndexes('obligations'))->pluck('name');

            // Boolean index — same problem, MySQL ignores it.
            if ($existing->contains('obligations_is_active_index')) {
                $table->dropIndex('obligations_is_active_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $existing = collect(Schema::getIndexes('transactions'))->pluck('name');

            if ($existing->contains('transactions_user_state_date_index')) {
                $table->dropIndex('transactions_user_state_date_index');
            }
            if (!$existing->contains('transactions_type_index')) {
                $table->index('type', 'transactions_type_index');
            }
            if (!$existing->contains('transactions_state_index')) {
                $table->index('state', 'transactions_state_index');
            }
        });

        Schema::table('obligations', function (Blueprint $table) {
            $existing = collect(Schema::getIndexes('obligations'))->pluck('name');

            if (!$existing->contains('obligations_is_active_index')) {
                $table->index('is_active', 'obligations_is_active_index');
            }
        });
    }
};
