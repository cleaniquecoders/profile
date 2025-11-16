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
        Schema::table('addresses', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('state');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('validation_status')->default('pending')->after('longitude');
            $table->timestamp('validated_at')->nullable()->after('validation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'validation_status', 'validated_at']);
        });
    }
};
