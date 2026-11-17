<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store each order line's subtotal so later catalog price edits never
     * change the historical total of an order.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('quantity');
        });

        DB::table('order_items')->update(['subtotal' => DB::raw('unit_price * quantity')]);
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
};
