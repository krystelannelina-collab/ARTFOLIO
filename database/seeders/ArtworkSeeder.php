<?php

namespace Database\Seeders;

use App\Models\Artwork;
use Illuminate\Database\Seeder;

class ArtworkSeeder extends Seeder
{
    public function run(): void
    {
        $artworks = [
            ['fluid-geometric', 'Fluid Geometric', 'Cristine Joy Montes', 'Abstract', 28.56, 'artwork-fluid-geometric.png', 'A study in movement and color, Fluid Geometric brings flowing shapes together in a bold, contemporary composition.'],
            ['stranger-things-poster', 'Stranger Things Poster', 'Gian Gomez', 'Poster', 35.76, 'artwork-stranger-things.png', 'An atmospheric poster pairing a bold red palette with a cinematic composition and dramatic lighting.'],
            ['minimalist-organic', 'Minimalist Organic', 'Gian Gomez', 'Abstract', 15.67, 'artwork-minimalist-organic.png', 'Soft, organic shapes and a restrained palette create a calm, modern addition to any collection.'],
            ['geometric-contrast', 'Geometric Contrast', 'Kalvin Dave Ramos', 'Abstract', 20.43, 'artwork-geometric-contrast.png', 'Crisp geometric forms meet expressive color in a composition built around contrast and balance.'],
            ['colorful-abstract', 'Colorful Abstract', 'Kalvin Dave Ramos', 'Abstract', 24.50, 'artwork-colorful-abstract.png', 'A vibrant abstract composition that brings expressive color and movement into focus.'],
            ['the-one-poster', 'The One Poster', 'Kalvin Dave Ramos', 'Poster', 25.86, 'artwork-the-one-poster.png', 'A striking poster design with a memorable focal point and a clean, graphic sensibility.'],
            ['geometric-abstract', 'Geometric Abstract', 'Krystel Anne Lina', 'Abstract', 21.43, 'artwork-geometric-abstract.png', 'Layered geometric forms create depth and rhythm in this imaginative abstract artwork.'],
            ['goldmidnight-pixel-art', 'GoldMidnight Pixel Art', 'Kalvin Dave Ramos', 'Pixel', 18.50, 'artwork-geometric-contrast.png', 'A pixel-inspired composition that brings a graphic, digital edge to a warm and moody palette.'],
            ['golden-mood-typography', 'Golden Mood Typography', 'Gian Gomez', 'Typography', 24.50, 'artwork-stranger-things.png', 'Expressive type and cinematic color make this artwork a bold statement for your space.'],
            ['modern-muse-digital', 'Modern Muse Digital', 'Cristine Joy Montes', 'Digital', 27.00, 'artwork-fluid-geometric.png', 'A contemporary digital artwork shaped by fluid forms, color, and a playful sense of movement.'],
        ];

        foreach ($artworks as [$slug, $title, $artistName, $category, $price, $imagePath, $description]) {
            Artwork::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'artist_name' => $artistName,
                    'category' => $category,
                    'price' => $price,
                    'image_path' => "images/{$imagePath}",
                    'description' => $description,
                ],
            );
        }
    }
}
