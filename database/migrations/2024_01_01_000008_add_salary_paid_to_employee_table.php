<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employee') && !Schema::hasColumn('employee', 'salary_paid')) {
            Schema::table('employee', function (Blueprint $table) {
                $table->float('salary_paid')->default(0)->after('salary');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employee') && Schema::hasColumn('employee', 'salary_paid')) {
            Schema::table('employee', function (Blueprint $table) {
                $table->dropColumn('salary_paid');
            });
        }
    }
};
