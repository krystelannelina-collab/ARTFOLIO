<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'headline', 'bio', 'image_path', 'facebook_url', 'github_url', 'instagram_url', 'email', 'sort_order'])]
class Artist extends Model
{
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }
}
