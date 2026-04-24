<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Adds transactions.obligation_id as a nullable FK to obligations(id).
// Additive only — historical rows keep NULL. The backfill and wiring to
// remove the fragile (user_id, description) match happens in a separate
// migration/PR, so this one can be applied in production without a
// maintenance window.
//
// ON DELETE SET NULL: deleting an obligation leaves its transactions
// standing (with obligation_id = NULL) so historical data isn't lost.
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('transactions', 'obligation_id')) {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignUuid('obligation_id')
                ->nullable()
                ->after('category_id')
                ->constrained('obligations')
                ->nullOnDelete();

            $table->index('obligation_id', 'transactions_obligation_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('transactions'))->pluck('name');
            $existingFks = collect(Schema::getForeignKeys('transactions'))->pluck('name');

            if ($existingIndexes->contains('transactions_obligation_id_index')) {
                $table->dropIndex('transactions_obligation_id_index');
            }

            if ($existingFks->contains('transactions_obligation_id_foreign')) {
                $table->dropForeign('transactions_obligation_id_foreign');
            }

            if (Schema::hasColumn('transactions', 'obligation_id')) {
                $table->dropColumn('obligation_id');
            }
        });
    }
};
