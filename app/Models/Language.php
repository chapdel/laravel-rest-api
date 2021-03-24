<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Translatable;
    protected $guarded = ['id'];
}
