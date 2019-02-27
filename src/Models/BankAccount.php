<?php

namespace CleaniqueCoders\Profile\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $guarded = ['id'];

    /**
     * [bank description].
     *
     * @return [type] [description]
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class)->withDefault();
    }

    /**
     * Get all of the owning bank models.
     */
    public function bankable()
    {
        return $this->morphTo();
    }

    /**
     * Get Bank Name via Accessor.
     *
     * @return string
     */
    public function getBankNameAttribute()
    {
        return $this->bank->name;
    }
}