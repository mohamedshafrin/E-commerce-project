<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockDeductedToOrdersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'stock_update')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('stock_update')->default(false)->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'stock_update')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('stock_update');
            });
        }
    }
}
