<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UnBanned implements Rule
{
    public string $table;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $d = DB::table($this->table)->where($attribute, $value)->where('banned_at', null)->first();

        if ($d) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This :attribute has been banned.';
    }
}
