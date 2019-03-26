<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Support\Str;
use Log;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        dump(111);die;
        if (!method_exists($response, 'content')) {
            return $response;
        }

//        $user = optional(auth('api')->user());
        $logContent =
            $request->url() . "\t" .
            ($request->userAgent()) . "\t" .
            ($request->ip()) . "\t" .
//            ($user->getKey() ?? '-') . "\t" .
//            ($user->name ?? '-') . "\t" .
            ('user-key' ?? '-') . "\t" .
            ('user-name' ?? '-') . "\t" .
            (empty($inputs = $request->all()) ? '{}' :
                json_encode($inputs, JSON_UNESCAPED_UNICODE)) . "\t" .
            Str::limit($response->content(), 1024) . "\t";

        Log::channel('request')->info($logContent);

        return $response
            ->header('name', $request->route()->getName());
    }
}
