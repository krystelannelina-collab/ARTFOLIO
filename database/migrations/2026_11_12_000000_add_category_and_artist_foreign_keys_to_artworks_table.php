<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Link every artwork to its category and artist rows with real foreign keys.
     * The existing name columns stay as display copies that the Artwork model
     * keeps in step with the linked rows.
     */
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained()->restrictOnDelete();
            $table->foreignId('artist_id')->nullable()->after('artist_name')->constrained()->restrictOnDelete();
        });

        foreach (DB::table('categories')->get(['id', 'name']) as $category) {
            DB::table('artworks')->where('category', $category->name)->update(['category_id' => $category->id]);
        }

        $artistIdsByName = DB::table('artists')->pluck('id', 'name')
            ->mapWithKeys(fn (int|string $id, string $name): array => [mb_strtolower($name) => (int) $id]);

        $artistNames = DB::table('artworks')->whereNull('artist_id')->distinct()->pluck('artist_name');

        foreach ($artistNames as $artistName) {
            $key = mb_strtolower(trim($artistName));

            if (! $artistIdsByName->has($key)) {
                $artistIdsByName[$key] = DB::table('artists')->insertGetId([
                    'name' => trim($artistName),
                    'slug' => $this->uniqueArtistSlug($artistName),
                    'bio' => 'Explore original works in the ARTFOLIO collection.',
                    'sort_order' => ((int) DB::table('artists')->max('sort_order')) + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('artworks')->where('artist_name', $artistName)->update(['artist_id' => $artistIdsByName[$key]]);
        }
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('artist_id');
        });
    }

    private function uniqueArtistSlug(string $name): string
    {
        $baseSlug = Str::slug($name) ?: 'artist';
        $slug = $baseSlug;
        $suffix = 2;

        while (DB::table('artists')->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
};
