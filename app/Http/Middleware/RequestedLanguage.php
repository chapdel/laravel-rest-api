<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class RequestedLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()) {
            $user = auth()->user();
            App::setLocale($user->config->language->iso_code);
        } else {
            if ($request->lang) {

                $val = Validator::make(['lang' => $request->lang], [
                    'lang' => [
                        "exists:languages,iso_code"
                    ]
                ]);

                if (!$val->fails()) {
                    App::setLocale($request->lang);
                }
            }
        }


        return $next($request);
    }
}
