<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameStockDeductedToStockUpdateOnOrdersTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (! Schema::hasColumn('orders', 'stock_update')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('stock_update')->default(false)->after('status');
            });
        }

        if (Schema::hasColumn('orders', 'stock_deducted')) {
            DB::table('orders')->update([
                'stock_update' => DB::raw('stock_deducted'),
            ]);

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('stock_deducted');
            });
        }
    }

    public function down()
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        if (! Schema::hasColumn('orders', 'stock_deducted')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('stock_deducted')->default(false)->after('status');
            });
        }

        if (Schema::hasColumn('orders', 'stock_update')) {
            DB::table('orders')->update([
                'stock_deducted' => DB::raw('stock_update'),
            ]);

            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('stock_update');
            });
        }
    }
}
