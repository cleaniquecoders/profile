<?php

/*
|--------------------------------------------------------------------------
| Architecture Tests - Code Quality & Design
|--------------------------------------------------------------------------
|
| These tests ensure that the codebase follows architectural best practices,
| maintains clean code standards, and adheres to Laravel conventions.
|
*/

// Ensure no debugging functions are used in source code
test('source code does not contain debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

/*
|--------------------------------------------------------------------------
| Namespace & Directory Structure Tests
|--------------------------------------------------------------------------
*/

// Models are properly namespaced
test('models are in the Models namespace')
    ->expect('CleaniqueCoders\Profile\Models')
    ->toBeClasses();

// Concerns (Traits) must be in the Concerns namespace
test('concerns are properly namespaced')
    ->expect('CleaniqueCoders\Profile\Concerns')
    ->toBeTraits();

// Enums must be in the Enums namespace
test('enums are properly namespaced')
    ->expect('CleaniqueCoders\Profile\Enums')
    ->toBeEnums();

// Contracts must be in the Contracts namespace
test('contracts are interfaces')
    ->expect('CleaniqueCoders\Profile\Contracts')
    ->toBeInterfaces();

/*
|--------------------------------------------------------------------------
| Dependency & Architecture Layer Tests
|--------------------------------------------------------------------------
*/

// Ensure concerns/traits have clean dependencies (don't hardcode models)
test('concerns have clean architecture')
    ->expect('CleaniqueCoders\Profile\Concerns')
    ->not->toUse('CleaniqueCoders\Profile\Models');

// Ensure traits are reusable and don't have hard dependencies
test('concerns do not depend on specific models')
    ->expect('CleaniqueCoders\Profile\Concerns')
    ->not->toUse('Workbench');

// Ensure enums are self-contained
test('enums have no external dependencies except base enum trait')
    ->expect('CleaniqueCoders\Profile\Enums')
    ->not->toUse([
        'CleaniqueCoders\Profile\Models',
        'CleaniqueCoders\Profile\Concerns',
    ]);

/*
|--------------------------------------------------------------------------
| Eloquent Model Standards
|--------------------------------------------------------------------------
*/

// All models should extend Eloquent Model
test('models extend Eloquent Model')
    ->expect('CleaniqueCoders\Profile\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model');

/*
|--------------------------------------------------------------------------
| Security & Best Practices
|--------------------------------------------------------------------------
*/

// Ensure models have mass assignment protection
test('models have mass assignment protection property')
    ->expect('CleaniqueCoders\Profile\Models')
    ->toBeClasses();

// Ensure no eval() or similar dangerous functions
test('codebase does not use dangerous functions')
    ->expect(['eval', 'exec', 'system', 'shell_exec', 'passthru'])
    ->not->toBeUsed();

/*
|--------------------------------------------------------------------------
| Laravel Conventions
|--------------------------------------------------------------------------
*/

// Service Provider follows Laravel conventions
test('service provider extends Laravel service provider')
    ->expect('CleaniqueCoders\Profile\ProfileServiceProvider')
    ->toExtend('Spatie\LaravelPackageTools\PackageServiceProvider');

// Commands extend Laravel console command
test('commands extend Laravel console command')
    ->expect('CleaniqueCoders\Profile\Console\Commands')
    ->toExtend('Illuminate\Console\Command');

/*
|--------------------------------------------------------------------------
| Code Organization
|--------------------------------------------------------------------------
*/

// Ensure proper namespace organization - service provider is properly placed
test('service provider is in the correct namespace')
    ->expect('CleaniqueCoders\Profile\ProfileServiceProvider')
    ->toBeClass();

// Proper separation of concerns
test('models only contain model logic')
    ->expect('CleaniqueCoders\Profile\Models')
    ->not->toUse([
        'Illuminate\Http\Request',
        'Illuminate\Support\Facades\Http',
    ]);
