<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryMovie extends Model
{
    protected $table = 'category_movies';
    protected $fillable = [
        'name', 'description', 'status',
    ];
}
