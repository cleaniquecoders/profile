<?php

namespace CleaniqueCoders\Profile;

use CleaniqueCoders\Profile\Console\Commands\SeedProfileCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ProfileServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('profile')
            ->hasConfigFile('profile')
            ->hasCommand(SeedProfileCommand::class)
            ->hasMigrations(
                'create_addresses_table',
                'create_banks_table',
                'create_countries_table',
                'create_credentials_table',
                'create_documents_table',
                'create_emails_table',
                'create_emergency_contacts_table',
                'create_phone_types_table',
                'create_phones_table',
                'create_social_media_table',
                'create_websites_table',
                'add_verification_to_emails_table',
                'add_verification_to_phones_table',
                'add_validation_to_addresses_table',
            );
    }
}
