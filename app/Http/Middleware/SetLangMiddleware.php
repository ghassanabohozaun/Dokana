<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // ما يهمني هنا انه ارجع الي البيانات من الداتابيز حسب اللغة اللي هو ارسلها ام ترجمة البيانات الثابتة عليه هو
        // get Lang From Header =>mobile developer send it in header from postman
        $lang = $request->header('set-lang', 'en');

        // define  supported langauges
        $supported = ['en', 'ar'];

        // check ig language is supported  , then set locale
        if ($lang && in_array($lang, $supported)) {
            App::setLocale($lang);
        }
        return $next($request);
    }
}
