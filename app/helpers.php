<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists("get_file_absolute_path")) {
    function get_file_absolute_path($path): String
    {
        return config('filesystems.default') != "minio" ? Storage::url($path) : Storage::temporaryUrl($path, now()->addDays(6));
    }
}
