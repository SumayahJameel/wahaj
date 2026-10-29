<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('quotes')->whereNotIn('user_id', DB::table('users')->select('id'))->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->string('content', 155)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->string('content')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
};
