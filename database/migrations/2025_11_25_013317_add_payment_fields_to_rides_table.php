<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable()->after('payment_status'); // paypal, stripe, cash
            $table->string('payment_id')->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_id');
        });
    }

    public function down()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'payment_id', 'paid_at']);
        });
    }
};