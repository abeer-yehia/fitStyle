<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Sluggable;

class Section extends Model
{
    use HasFactory, Sluggable;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
