<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('level', 50)->default('operator_putra')->change();
        });
        
        \Illuminate\Support\Facades\DB::table('users')
            ->where('level', 'operator')
            ->update(['level' => 'operator_putra']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('level', 50)->default('operator')->change();
        });
    }
};
