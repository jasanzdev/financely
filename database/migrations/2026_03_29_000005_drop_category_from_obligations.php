<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // Drops the obligations.category free-text column.
    // The column was displayed as a UI badge and stored as plain string, but was
    // never used to categorize the generated transactions — those were always
    // assigned to the hardcoded "Obligaciones Mensuales" category regardless.
    // Removing it eliminates the gap between what the user fills in and what
    // the system actually does with that value.
    public function up(): void
    {
        Schema::table('obligations', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('obligations', function (Blueprint $table) {
            // Nullable so that restoring the column on a table with existing rows
            // does not require a default value for every row.
            $table->string('category')->nullable()->after('description');
        });
    }
};
