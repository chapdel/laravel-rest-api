<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeApps($query)
    {
        return $query->whereType("app");
    }

    public function scopeGames($query)
    {
        return $query->whereType("game");
    }
}
