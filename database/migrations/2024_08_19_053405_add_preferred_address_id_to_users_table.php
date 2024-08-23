z<?php

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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('preferred_address_id')->nullable()->after('is_verified');
        $table->foreign('preferred_address_id')->references('id')->on('vege_address')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['preferred_address_id']);
        $table->dropColumn('preferred_address_id');
    });
}

};
