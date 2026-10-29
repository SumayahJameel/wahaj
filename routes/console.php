<?php

use App\Models\Quote;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('quotes:clear-old', function () {
    $oldQuotes = Quote::whereHas('user', fn ($query) => $query->where('email', 'not like', '%@wahaj.test'));
    $count = $oldQuotes->count();

    if ($count === 0) {
        $this->info('No old quotes to delete.');

        return;
    }

    if (! $this->confirm("Delete {$count} old quotes permanently?", true)) {
        $this->comment('Nothing was deleted.');

        return;
    }

    $oldQuotes->get()->each->delete();
    $this->info("Deleted {$count} old quotes.");
})->purpose('Delete every quote not written by the demo accounts');
