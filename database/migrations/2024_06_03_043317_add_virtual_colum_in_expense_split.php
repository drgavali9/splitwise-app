<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('expense_splits', function (Blueprint $table) {
            $table->string('party_unique_id')->virtualAs("CONCAT(group_id, '-', paid_user_id, '-', receive_user_id)")->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('expense_splits', function (Blueprint $table) {
            $table->dropColumn('party_unique_id');
        });
    }
};
