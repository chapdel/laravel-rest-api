<?php

namespace App\Models;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }
}
