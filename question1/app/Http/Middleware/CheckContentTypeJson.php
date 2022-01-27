<?php

namespace App\Http\Middleware;

use App\Exceptions\ActionException;

use Closure;

class CheckContentTypeJson
{
    /**
     * 確認進來的 request 的 Content-Type 是否為 json 格式。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->headers->get('Content-Type') !== 'application/json') {
            throw new ActionException(ActionException::ERROR_CONTENT_TYPE_JSON_HEADER);
        }

        json_decode($request->getContent());

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ActionException(ActionException::ERROR_CONTENT_TYPE_JSON_BODY);
        }

        return $next($request);
    }
}
