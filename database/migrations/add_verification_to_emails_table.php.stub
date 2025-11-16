<?php

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
        Schema::table('emails', function (Blueprint $table) {
            $table->string('verification_token')->nullable()->after('email');
            $table->timestamp('verification_token_expires_at')->nullable()->after('verification_token');
            $table->timestamp('verified_at')->nullable()->after('verification_token_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'verification_token_expires_at', 'verified_at']);
        });
    }
};
