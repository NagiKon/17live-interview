<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Closure;

class Logging
{
    /**
     * 針對 Request 與 Response 紀錄 Log。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userIp     = $request->ip();
        $method     = $request->method();
        $requestUrl = $request->url();

        $this->logRequest($request, $userIp, $method, $requestUrl);

        $response = $next($request);
        $this->logResponse($response, $userIp, $method, $requestUrl);

        return $response;
    }

    private function logRequest(Request $request, $userIp, $method, $requestUrl): void
    {
        $log = sprintf('The %s try to %s the %s. The information of request is:', $userIp, $method, $requestUrl);
        Log::warning($log, ['Request' => $request]);
    }

    private function logResponse($response, $userIp, $method, $requestUrl): void
    {
        $log = sprintf("The information of response which %s is been %s by %s is:", $requestUrl, $method, $userIp);
        Log::info($log, ['Response' => $response]);
    }
}
