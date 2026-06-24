<?php

namespace Database\Seeders;

use App\Models\PaymentEntity;
use Illuminate\Database\Seeder;

class PaymentEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['ar' => 'بنك فلسطين', 'en' => 'Bank of Palestine'],
            ['ar' => 'البنك الإسلامي العربي', 'en' => 'Arab Islamic Bank'],
            ['ar' => 'البنك الإسلامي الفلسطيني', 'en' => 'Palestine Islamic Bank'],
            ['ar' => 'بنك القدس', 'en' => 'Quds Bank'],
            ['ar' => 'البنك الوطني', 'en' => 'The National Bank'],
        ];

        $wallets = [
            ['ar' => 'جوال باي', 'en' => 'Jawwal Pay'],
            ['ar' => 'بال باي', 'en' => 'PalPay'],
            ['ar' => 'أوريدو موني', 'en' => 'Ooredoo Money'],
        ];

        $cashBoxes = [
            ['ar' => 'صندوق الكاش الرئيسي', 'en' => 'Main Cash Box'],
        ];

        foreach ($banks as $bank) {
            PaymentEntity::firstOrCreate(
                ['name->ar' => $bank['ar']],
                [
                    'name' => ['ar' => $bank['ar'], 'en' => $bank['en']],
                    'type' => 'bank',
                    'status' => 1,
                ]
            );
        }

        foreach ($wallets as $wallet) {
            PaymentEntity::firstOrCreate(
                ['name->ar' => $wallet['ar']],
                [
                    'name' => ['ar' => $wallet['ar'], 'en' => $wallet['en']],
                    'type' => 'wallet',
                    'status' => 1,
                ]
            );
        }

        foreach ($cashBoxes as $cash) {
            PaymentEntity::firstOrCreate(
                ['name->ar' => $cash['ar']],
                [
                    'name' => ['ar' => $cash['ar'], 'en' => $cash['en']],
                    'type' => 'cash',
                    'status' => 1,
                ]
            );
        }
    }
}
