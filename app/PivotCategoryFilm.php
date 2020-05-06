<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PivotCategoryFilm extends Model
{
    protected $table = 'pivot_category_film';
    protected $fillable = [
        'film_id', 'category_id', 'status',
    ];
}
