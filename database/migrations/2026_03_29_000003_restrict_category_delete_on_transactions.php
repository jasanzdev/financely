<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Changes the category_id FK on transactions from CASCADE (default) to RESTRICT.
    // With CASCADE, deleting a category silently erased all its transactions.
    // With RESTRICT, the DB rejects the delete if any transaction references that category,
    // making the PHP guard in CategoryIndex::delete() redundant but harmless.
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return; // SQLite manages FKs at table-creation time; no-op for tests
        }

        Schema::table('transactions', function (Blueprint $table) {
            $existing = collect(Schema::getForeignKeys('transactions'))->pluck('name');

            if ($existing->contains('transactions_category_id_foreign')) {
                $table->dropForeign('transactions_category_id_foreign');
            }

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('transactions', function (Blueprint $table) {
            $existing = collect(Schema::getForeignKeys('transactions'))->pluck('name');

            if ($existing->contains('transactions_category_id_foreign')) {
                $table->dropForeign('transactions_category_id_foreign');
            }

            $table->foreignUuid('category_id')->constrained();
        });
    }
};
