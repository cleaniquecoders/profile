<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
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
            \CLNQCDRS\Profile\Models\PhoneType::create([
                'name'  => $datum,
                'label' => Str::slug($datum, '-'),
            ]);
        }
    }
}
