<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'like', '%@qabas.test')
            ->update(['email' => DB::raw("replace(email, '@qabas.test', '@wahaj.test')")]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'like', '%@wahaj.test')
            ->update(['email' => DB::raw("replace(email, '@wahaj.test', '@qabas.test')")]);
    }
};
