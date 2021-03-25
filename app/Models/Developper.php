<?php

namespace App\Models;

use App\Traits\Translatable;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Developper extends Model
{
    use HasFactory;
    use Translatable;
    use SoftDeletes;
    use Bannable;

    protected $guarded = ['id'];

    public function apps()
    {
        return $this->hasMany(App::class);
    }
}
