<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            'Home',
            'Mobile',
            'Office',
            'Other',
        ];

        foreach ($data as $datum) {
            \CleaniqueCoders\Profile\Models\PhoneType::create([
                'name'  => $datum,
                'label' => Str::slug($datum, '-'),
            ]);
        }
    }
}
