<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('final_amount', 10, 2)->after('discount_amount');
            $table->unsignedBigInteger('promocode_id')->nullable()->after('phone_id');
            
            $table->string('status')->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'final_amount', 'promocode_id']);
        });
    }
};