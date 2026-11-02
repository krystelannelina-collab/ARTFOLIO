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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('bio', 500)->default('');
            $table->string('image_path')->default('images/artist-cristine-joy-montes.png');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('sort_order');
        });

        $defaultArtists = [
            [
                'name' => 'Cristine Joy Montes',
                'bio' => 'Flowing shapes, bold forms, and a fresh perspective.',
                'image_path' => 'images/artist-cristine-joy-montes.png',
                'sort_order' => 1,
            ],
            [
                'name' => 'Gian Gomez',
                'bio' => 'From cinematic posters to expressive abstract pieces.',
                'image_path' => 'images/artist-gian-gomez.png',
                'sort_order' => 2,
            ],
            [
                'name' => 'Kalvin Dave Ramos',
                'bio' => 'Explore color, contrast, and expressive compositions.',
                'image_path' => 'images/artist-kalvin-dave-ramos.png',
                'sort_order' => 3,
            ],
            [
                'name' => 'Krystel Anne Lina',
                'bio' => 'Discover layered forms and imaginative geometry.',
                'image_path' => 'images/artist-krystel-anne-lina.png',
                'sort_order' => 4,
            ],
        ];

        foreach ($defaultArtists as $artist) {
            DB::table('artists')->insert([
                'name' => $artist['name'],
                'slug' => Str::slug($artist['name']),
                'bio' => $artist['bio'],
                'image_path' => $artist['image_path'],
                'sort_order' => $artist['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
