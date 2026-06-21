<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'QAR', 'name_ar' => 'ريال قطري', 'name_en' => 'Qatari Riyal', 'symbol_ar' => 'ر.ق', 'symbol_en' => 'QAR'],
            ['code' => 'SAR', 'name_ar' => 'ريال سعودي', 'name_en' => 'Saudi Riyal', 'symbol_ar' => 'ر.س', 'symbol_en' => 'SAR'],
            ['code' => 'AED', 'name_ar' => 'درهم إماراتي', 'name_en' => 'UAE Dirham', 'symbol_ar' => 'د.إ', 'symbol_en' => 'AED'],
            ['code' => 'KWD', 'name_ar' => 'دينار كويتي', 'name_en' => 'Kuwaiti Dinar', 'symbol_ar' => 'د.ك', 'symbol_en' => 'KWD'],
            ['code' => 'BHD', 'name_ar' => 'دينار بحريني', 'name_en' => 'Bahraini Dinar', 'symbol_ar' => 'د.ب', 'symbol_en' => 'BHD'],
            ['code' => 'OMR', 'name_ar' => 'ريال عماني', 'name_en' => 'Omani Rial', 'symbol_ar' => 'ر.ع', 'symbol_en' => 'OMR'],
            ['code' => 'USD', 'name_ar' => 'دولار أمريكي', 'name_en' => 'US Dollar', 'symbol_ar' => '$', 'symbol_en' => '$'],
            ['code' => 'EUR', 'name_ar' => 'يورو', 'name_en' => 'Euro', 'symbol_ar' => '€', 'symbol_en' => '€'],
            ['code' => 'GBP', 'name_ar' => 'جنيه إسترليني', 'name_en' => 'British Pound', 'symbol_ar' => '£', 'symbol_en' => '£'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
