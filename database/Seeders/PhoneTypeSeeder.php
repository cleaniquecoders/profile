<?php

namespace CleaniqueCoders\Profile\Database\Seeders;

use CleaniqueCoders\Profile\Enums\PhoneType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PhoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $phoneTypes = PhoneType::cases();

        foreach ($phoneTypes as $phoneType) {
            \CleaniqueCoders\Profile\Models\PhoneType::create([
                'uuid' => Str::orderedUuid(),
                'name' => $phoneType->label(),
                'label' => $phoneType->value,
            ]);
        }
    }
}
