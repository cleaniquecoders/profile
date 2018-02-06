<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->hashslug();
            $table->addNullableForeign('country_id', 'countries');
            $table->unsignedInteger('addressable_id');
            $table->string('addressable_type');
            $table->text('primary')->nullable();
            $table->text('secondary')->nullable();
            $table->string('postcode')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->boolean('is_default')->default(false);
            $table->standardTime();

            $table->referenceOn('country_id', 'countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
