<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;


class VerifyCsrfToken extends Middleware
{
    public function getRoute(){
        $request = Request::createFromGlobals();
    $host = $request->getHost();
    $protocol = $request->getScheme();
    $route = $protocol . "://" . $host . "/{username}";
    return $route;
    }
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'likePost/*',
        "visualization/*",
        "bookmarks/*",
        "post/*",
        "*/posts",
        "*/follow",
        "*/answers/details",
        "*/likes/details"
    ];
}
