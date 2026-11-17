<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'bio', 'image_path', 'sort_order'])]
class Artist extends Model
{
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }
}
