<?php

namespace App\Helpers;

class RouteHelper
{
    public static function RedirectTo($routeName)
    {
        return redirect()->route($routeName);
    }
}
