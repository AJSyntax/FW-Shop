<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'FandomWearShop'
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@fandomwearshop.com'
            ],
            [
                'key' => 'min_order_amount',
                'value' => '10.00'
            ],
            [
                'key' => 'tax_rate',
                'value' => '7.00'
            ],
            [
                'key' => 'order_confirmation_email',
                'value' => true
            ],
            [
                'key' => 'inventory_alerts',
                'value' => true
            ]
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
} 