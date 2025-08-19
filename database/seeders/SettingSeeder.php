<?php

namespace Database\Seeders;

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
        Setting::create([
            'key' => Setting::KEY_PAYMENT_AMOUNT,
            'value' => '99000'
        ]);

        Setting::create([
            'key' => Setting::KEY_PAYMENT_RATE_PERCENT,
            'value' => '3'
        ]);
    }

}
