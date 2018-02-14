<?php

namespace CleaniqueCoders\Profile\Console\Commands;

use Illuminate\Console\Command;

class SeedProfileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Profile Data';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('db:seed', ['--class' => 'CountrySeeder']);
        $this->call('db:seed', ['--class' => 'PhoneTypeSeeder']);
    }
}
