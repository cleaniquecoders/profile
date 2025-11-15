<?php

use CleaniqueCoders\Profile\Models\Bank;
use CleaniqueCoders\Profile\Models\BankAccount;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

beforeEach(function () {
    Artisan::call('profile:seed');
    $this->user = User::factory()->create();
    $this->bank = Bank::first();
});

it('has a bank_accounts table', function () {
    expect(Schema::hasTable('bank_accounts'))->toBeTrue();
});

it('bank account model can be created with required fields', function () {
    $bankAccount = BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '1234567890',
        'is_default' => true,
    ]);

    expect($bankAccount)->not->toBeNull()
        ->and($bankAccount->account_no)->toBe('1234567890')
        ->and($bankAccount->is_default)->toBeTrue()
        ->and($bankAccount->bank_id)->toBe($this->bank->id);
});

it('bank account belongs to a bank', function () {
    $bankAccount = BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '1234567890',
        'is_default' => false,
    ]);

    expect($bankAccount->bank)->toBeInstanceOf(Bank::class)
        ->and($bankAccount->bank->id)->toBe($this->bank->id);
});

it('bank account has polymorphic relationship to bankable model', function () {
    $bankAccount = BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '9999999999',
        'is_default' => false,
    ]);

    expect($bankAccount->bankable)->toBeInstanceOf(User::class)
        ->and($bankAccount->bankable->id)->toBe($this->user->id)
        ->and($bankAccount->bankable_type)->toBe(get_class($this->user));
});

it('bank account can access bank name via accessor', function () {
    $bankAccount = BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '1111111111',
        'is_default' => false,
    ]);

    expect($bankAccount->bank_name)->toBe($this->bank->name);
});

it('can create multiple bank accounts for the same owner', function () {
    BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '1111111111',
        'is_default' => false,
    ]);

    BankAccount::create([
        'bank_id' => $this->bank->id,
        'bankable_id' => $this->user->id,
        'bankable_type' => get_class($this->user),
        'account_no' => '2222222222',
        'is_default' => true,
    ]);

    $bankAccounts = BankAccount::where('bankable_id', $this->user->id)->get();

    expect($bankAccounts)->toHaveCount(2);
});
