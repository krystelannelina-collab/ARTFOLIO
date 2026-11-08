<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->string('availability')->default('available')->index();
            $table->timestamp('sold_at')->nullable();
            $table->string('original_path')->nullable();
            $table->unsignedInteger('image_width')->nullable();
            $table->unsignedInteger('image_height')->nullable();
            $table->string('file_format', 10)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('canvas_size', 100)->nullable();
        });

        $soldArtworkIds = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereNotNull('order_items.artwork_id')
            ->distinct()
            ->pluck('order_items.artwork_id');

        DB::table('artworks')
            ->whereIn('id', $soldArtworkIds)
            ->update(['availability' => 'sold', 'sold_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropIndex(['availability']);
            $table->dropColumn([
                'availability',
                'sold_at',
                'original_path',
                'image_width',
                'image_height',
                'file_format',
                'file_size',
                'canvas_size',
            ]);
        });
    }
};
