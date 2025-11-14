# Working with Bank Accounts

Learn how to manage bank account information using the `Bankable` trait.

## Setup

Add the `Bankable` trait to your model:

```php
namespace App\Models;

use CleaniqueCoders\Profile\Concerns\Bankable;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use Bankable;
}
```

## Creating Bank Accounts

### Basic Bank Account Creation

```php
use CleaniqueCoders\Profile\Models\Bank;

$employee = Employee::find(1);

$bankAccount = $employee->banks()->create([
    'bank_id' => Bank::where('name', 'Maybank')->first()->id,
    'account_number' => '1234567890',
    'account_holder_name' => 'John Doe',
]);
```

### Creating Multiple Bank Accounts

```php
// Primary bank account
$employee->banks()->create([
    'bank_id' => Bank::where('code', 'MBB')->first()->id,
    'account_number' => '1234567890',
    'account_holder_name' => 'John Doe',
]);

// Secondary bank account
$employee->banks()->create([
    'bank_id' => Bank::where('name', 'CIMB Bank')->first()->id,
    'account_number' => '0987654321',
    'account_holder_name' => 'John Doe',
]);
```

## Retrieving Bank Accounts

### Get All Bank Accounts

```php
$bankAccounts = $employee->banks;

foreach ($bankAccounts as $account) {
    echo $account->account_number;
    echo $account->bank->name;
}
```

### Get with Bank Details

```php
$accounts = $employee->banks()->with('bank')->get();

foreach ($accounts as $account) {
    echo "{$account->bank->name}: {$account->account_number}";
}
```

### Filter by Bank

```php
// Get Maybank accounts
$maybankAccounts = $employee->banks()
    ->whereHas('bank', function($query) {
        $query->where('name', 'Maybank');
    })
    ->get();
```

## Updating Bank Accounts

```php
$account = $employee->banks()->first();

$account->update([
    'account_number' => '9999999999',
    'account_holder_name' => 'John David Doe',
]);
```

## Deleting Bank Accounts

```php
$account = $employee->banks()->first();
$account->delete(); // Soft delete
```

## Advanced Patterns

### Helper Methods

```php
class Employee extends Model
{
    use Bankable;

    /**
     * Get primary bank account
     */
    public function getPrimaryBankAccount()
    {
        return $this->banks()->first();
    }

    /**
     * Get bank account details
     */
    public function getBankAccountInfo(): array
    {
        $account = $this->getPrimaryBankAccount();

        if (!$account) {
            return [];
        }

        return [
            'bank_name' => $account->bank->name,
            'account_number' => $account->account_number,
            'account_holder' => $account->account_holder_name,
            'swift_code' => $account->bank->swift_code,
        ];
    }
}
```

### Validation

```php
public function storeBankAccount(Request $request, Employee $employee)
{
    $validated = $request->validate([
        'bank_id' => 'required|exists:banks,id',
        'account_number' => 'required|string|max:50',
        'account_holder_name' => 'required|string|max:255',
    ]);

    return $employee->banks()->create($validated);
}
```

### Encryption

```php
// Add encrypted column
Schema::table('bank_accounts', function (Blueprint $table) {
    $table->text('account_number_encrypted');
});

// Extended model with encryption
class BankAccount extends \CleaniqueCoders\Profile\Models\BankAccount
{
    protected $hidden = ['account_number'];

    public function setAccountNumberAttribute($value)
    {
        $this->attributes['account_number_encrypted'] = encrypt($value);
        $this->attributes['account_number'] = '****' . substr($value, -4);
    }

    public function getDecryptedAccountNumberAttribute()
    {
        return decrypt($this->account_number_encrypted);
    }
}
```

## Common Use Cases

### Payroll System

```php
class Employee extends Model
{
    use Bankable;

    public function processPayroll(float $amount)
    {
        $account = $this->getPrimaryBankAccount();

        if (!$account) {
            throw new \Exception('No bank account on file');
        }

        // Process payment to $account
        return [
            'bank' => $account->bank->name,
            'account' => $account->account_number,
            'holder' => $account->account_holder_name,
            'amount' => $amount,
        ];
    }
}
```

### Vendor Payments

```php
class Vendor extends Model
{
    use Bankable;

    public function getPaymentInstructions(): array
    {
        $account = $this->banks()->first();

        return [
            'beneficiary_name' => $account->account_holder_name,
            'bank_name' => $account->bank->name,
            'account_number' => $account->account_number,
            'swift_code' => $account->bank->swift_code ?? 'N/A',
            'bank_code' => $account->bank->code ?? 'N/A',
        ];
    }
}
```

## What's Next?

- [Advanced Queries](06-advanced-queries.md) - Complex query examples
- [Best Practices](07-best-practices.md) - Tips and recommendations
