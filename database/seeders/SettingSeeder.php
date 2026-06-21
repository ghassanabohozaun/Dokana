<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::first();

        Setting::firstOr(function () use ($store) {
            return Setting::create([
                'store_id' => $store->id,
                'site_name' => [
                    'en' => 'Dokana',
                    'ar' => 'دكانة',
                ],
                'address' => [
                    'en' => '',
                    'ar' => '',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                ],
                'keywords' => [
                    'en' => '',
                    'ar' => '',
                ],
                'phone' => '',
                'mobile' => '',
                'whatsapp' => '',
                'email' => '',
                'email_support' => '',
                'facebook' => '',
                'twitter' => '',
                'instegram' => '',
                'youtube' => '',
                'logo' => '',
                'favicon' => '',
                'auth_welcome_title' => [
                    'ar' => 'التميز في إدارة المتاجر',
                    'en' => 'Excellence in Store Management',
                ],
                'auth_welcome_desc' => [
                    'ar' => 'رؤية طموحة لمستقبل واعد. نحن نبني النجاح معاً من خلال الالتزام والابتكار.',
                    'en' => 'An ambitious vision for a promising future. We build success together through commitment and innovation.',
                ],
                'auth_welcome_badge' => [
                    'ar' => 'Dokana System',
                    'en' => 'Dokana System',
                ],
                'auth_welcome_footer' => [
                    'ar' => 'Dokana Portal',
                    'en' => 'Dokana Portal',
                ],
            ]);
        });
    }
}
