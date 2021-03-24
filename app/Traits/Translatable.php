<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait Translatable
{
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function translatable(): MorphTo
    {
        return $this->morphTo('translatable');
    }


    public function createdBy(): MorphTo
    {
        return $this->morphTo('created_by');
    }
}
