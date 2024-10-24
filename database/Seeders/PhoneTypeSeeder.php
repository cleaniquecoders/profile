<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = config('profile.data.phoneType');

        foreach ($data as $datum) {
            \CleaniqueCoders\Profile\Models\PhoneType::create([
                'uuid' => Str::orderedUuid(),
                'name' => $datum,
                'label' => Str::slug($datum, '-'),
            ]);
        }
    }
}
