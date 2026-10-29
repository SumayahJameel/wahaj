<?php

namespace Database\Seeders;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WahajSeeder extends Seeder
{
    /**
     * Seed demo accounts with quotes and likes; safe to run more than once.
     */
    public function run(): void
    {
        $password = Hash::make('password123');

        $people = [
            'sara' => ['name' => 'سارة العتيبي', 'email' => 'sara@wahaj.test'],
            'omar' => ['name' => 'Omar Haddad', 'email' => 'omar@wahaj.test'],
            'noura' => ['name' => 'نورة القحطاني', 'email' => 'noura@wahaj.test'],
            'lina' => ['name' => 'Lina Park', 'email' => 'lina@wahaj.test'],
            'abdullah' => ['name' => 'عبدالله الشهري', 'email' => 'abdullah@wahaj.test'],
            'maya' => ['name' => 'Maya Chen', 'email' => 'maya@wahaj.test'],
        ];

        $users = collect($people)->map(fn (array $person) => User::firstOrCreate(
            ['email' => $person['email']],
            ['name' => $person['name'], 'password' => $password, 'email_verified_at' => now()],
        ));

        $quotes = [
            ['sara', 'الكلمة الطيبة فانوس؛ تضيء الطريق لمن ضلّ، وتبقى مشتعلة في القلب حتى بعد أن يمضي قائلها.', 2],
            ['omar', 'A journey of a thousand miles begins with a single step. — Lao Tzu', 5],
            ['noura', 'على قدر أهل العزم تأتي العزائمُ، وتأتي على قدر الكرام المكارمُ. — المتنبي', 9],
            ['lina', 'Hope is the thing with feathers that perches in the soul. — Emily Dickinson', 14],
            ['abdullah', 'من جدّ وجد، ومن زرع حصد.', 20],
            ['maya', 'Small lights, held together, can turn the whole night gold.', 26],
            ['sara', 'ليس كلُّ ضوءٍ شمسًا؛ بعضُ النور كلمة.', 31],
            ['omar', 'The best way out is always through. — Robert Frost', 38],
            ['noura', 'إذا غامرتَ في شرفٍ مرومِ، فلا تقنعْ بما دون النجومِ. — المتنبي', 44],
            ['lina', 'This above all: to thine own self be true. — Shakespeare', 50],
            ['abdullah', 'خيرُ الكلام ما قلّ ودلّ.', 57],
            ['maya', 'Write the sentence you needed to read yesterday; someone needs it today.', 63],
            ['sara', 'كن أنت النور الذي تبحث عنه.', 70],
            ['omar', 'Stars cannot shine without darkness, so keep your light on.', 76],
            ['noura', 'الصبرُ مفتاحُ الفرج.', 83],
            ['lina', 'What you seek is seeking you. Keep walking toward the light.', 90],
            ['abdullah', 'لكلّ مجتهدٍ نصيب، ولكلّ ليلٍ فجرٌ يليه.', 98],
            ['maya', 'Be the lantern, not the shadow it leaves behind.', 105],
            ['sara', 'رُبّ كلمةٍ قالت لصاحبها: دعني.', 112],
            ['omar', 'Kindness is a language the deaf can hear and the blind can see.', 120],
            ['noura', 'العلمُ نورٌ، والجهلُ ظلامٌ يطفئ أجمل ما فينا.', 128],
            ['lina', 'Every sunrise is a new chapter; write it gently.', 136],
            ['abdullah', 'تفاءلوا بالخير تجدوه.', 145],
            ['maya', 'Hold on to the words that light you up, and pass them on.', 154],
            ['sara', 'في كل قلبٍ قنديلٌ ينتظر من يُشعله.', 163],
            ['omar', 'Little by little, one travels far.', 172],
            ['noura', 'أعذبُ الكلام ما خرج من القلب، فإنه يقع في القلب.', 182],
        ];

        $created = collect($quotes)->map(function (array $quote) use ($users) {
            [$author, $content, $hoursAgo] = $quote;

            $model = Quote::firstOrCreate(
                ['user_id' => $users[$author]->id, 'content' => $content],
            );

            if ($model->wasRecentlyCreated) {
                $model->forceFill([
                    'created_at' => now()->subHours($hoursAgo),
                    'updated_at' => now()->subHours($hoursAgo),
                ])->save();
            }

            return $model;
        });

        $likePlan = [
            'sara' => [1, 2, 3, 7, 9, 13, 15, 20, 21],
            'omar' => [0, 2, 4, 6, 9, 12, 14, 16, 22, 24],
            'noura' => [0, 1, 3, 5, 7, 11, 12, 17, 19, 23],
            'lina' => [0, 2, 6, 8, 10, 12, 14, 18, 20, 26],
            'abdullah' => [0, 1, 2, 5, 9, 12, 13, 16, 21, 25],
            'maya' => [0, 2, 3, 6, 9, 11, 12, 15, 20, 26],
        ];

        foreach ($likePlan as $person => $indexes) {
            $quoteIds = collect($indexes)
                ->map(fn (int $index) => $created[$index] ?? null)
                ->filter(fn (?Quote $quote) => $quote && $quote->user_id !== $users[$person]->id)
                ->pluck('id')
                ->all();

            $users[$person]->likedQuotes()->syncWithoutDetaching($quoteIds);
        }
    }
}
