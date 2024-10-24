<?php

use CleaniqueCoders\Profile\Models\Bank;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('swift_code', 20)->nullable()->index();
            $table->string('bank_code', 20)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->foreignIdFor(Bank::class);
            $table->unsignedInteger('bankable_id');
            $table->string('bankable_type');
            $table->string('account_no')->nullable();
            $table->boolean('is_default')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('banks');
    }
};
