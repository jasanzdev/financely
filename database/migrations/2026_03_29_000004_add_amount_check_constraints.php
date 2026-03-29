<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    // Adds CHECK (amount >= 0) to transactions and obligations.
    // Validation rules in forms already reject negatives, but DB-level constraints
    // protect against inserts via Tinker, seeds, bulk insert(), or future code paths
    // that bypass the form layer.
    // MySQL 8.0.16+ enforces CHECK constraints at the storage engine level.
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return; // SQLite only supports CHECK at table-creation time
        }

        DB::statement('ALTER TABLE transactions ADD CONSTRAINT chk_transactions_amount CHECK (amount >= 0)');
        DB::statement('ALTER TABLE obligations  ADD CONSTRAINT chk_obligations_amount  CHECK (amount >= 0)');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE transactions DROP CHECK chk_transactions_amount');
        DB::statement('ALTER TABLE obligations  DROP CHECK chk_obligations_amount');
    }
};
