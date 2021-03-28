<?php

namespace App\Models;

use Antonrom\ModelChangesHistory\Traits\HasChangesHistory;
use App\Traits\Translatable;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Developper extends Model
{
    use HasFactory;
    use Translatable;
    use SoftDeletes;
    use Bannable;
    use Notifiable;
    use HasChangesHistory;

    protected $guarded = ['id'];
    protected $with = ['apps'];
    protected $hidden = ['id', 'country_id', 'user_id', 'deleted_at', 'banned_at'];

    public function apps()
    {
        return $this->hasMany(App::class);
    }

    public function getPosterAttribute($value)
    {
        return $value ? get_file_absolute_path($value) : null;
    }

    public static function slug()
    {
        $r = Str::random(9);

        if (self::whereSlug($r)->first()) {
            return self::slug();
        }
        return $r;
    }
}
