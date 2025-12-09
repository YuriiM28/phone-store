<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->json('specifications')->nullable()->after('processor');
        });
    }

    public function down()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->dropColumn('specifications');
        });
    }
};