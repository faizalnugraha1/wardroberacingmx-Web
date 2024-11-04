<?php

namespace App\Http\Controllers\Trait;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

trait MetaTrait
{

    public static function set($data)
    {
        Session::flash('meta', $data);
    }
}
