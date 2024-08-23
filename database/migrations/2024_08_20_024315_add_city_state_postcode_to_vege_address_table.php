<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityStatePostcodeToVegeAddressTable extends Migration
{
    public function up()
    {
        Schema::table('vege_address', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address_2');
            $table->string('state')->nullable()->after('city');
            $table->string('postcode')->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('vege_address', function (Blueprint $table) {
            $table->dropColumn(['city', 'state', 'postcode']);
        });
    }
}
