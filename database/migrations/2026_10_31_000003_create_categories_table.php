<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description', 500)->default('');
            $table->boolean('is_visible')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });

        DB::table('artworks')->whereNull('category')->orWhere('category', '')->update(['category' => 'Uncategorized']);

        $descriptions = [
            'Abstract' => 'Shapes, color, and expressive compositions',
            'Digital' => 'Digital illustrations and creative scenes',
            'Pixel' => 'Pixel-based artwork and retro-inspired pieces',
            'Poster' => 'Cinematic posters and graphic compositions',
            'Typography' => 'Lettering and type-driven artwork',
        ];
        $categoryNames = collect(array_keys($descriptions))
            ->merge(DB::table('artworks')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'))
            ->map(fn (string $name): string => trim($name) ?: 'Uncategorized')
            ->unique()
            ->sort()
            ->values();

        foreach ($categoryNames as $sortOrder => $categoryName) {
            $categoryName = trim((string) $categoryName);
            if ($categoryName === '') {
                $categoryName = 'Uncategorized';
            }

            $slug = Str::slug($categoryName) ?: 'category';
            $suffix = 2;
            while (DB::table('categories')->where('slug', $slug)->exists()) {
                $slug = Str::slug($categoryName).'-'.$suffix;
                $suffix++;
            }

            DB::table('categories')->insert([
                'name' => $categoryName,
                'slug' => $slug,
                'description' => $descriptions[$categoryName] ?? 'Artwork styles in your catalog',
                'is_visible' => true,
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('artworks')->where('category', '')->update(['category' => 'Uncategorized']);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
