<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Translatable;
    protected $table = 'countries';
    protected $guarded = ['id'];
}
