<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'is_visible', 'sort_order'])]
class Category extends Model
{
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class);
    }

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}
