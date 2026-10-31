<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'title', 'artist_name', 'category', 'price', 'image_path', 'description'])]
class Artwork extends Model {}
