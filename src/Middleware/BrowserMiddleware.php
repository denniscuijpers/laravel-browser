<?php

declare(strict_types=1);

namespace DennisCuijpers\Browser\Middleware;

use Carbon\Carbon;
use Closure;
use DennisCuijpers\Browser\Browser;
use DennisCuijpers\Browser\Utils;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class BrowserMiddleware
{
    /**
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        $hasDeviceId = true;
        $deviceId    = $request->headers->get(Browser::HEADER_DEVICE_ID, $request->cookie(Browser::COOKIE_DEVICE_ID));

        if (!Utils::isUuid4((string) $deviceId)) {
            $hasDeviceId = false;
            $deviceId    = Utils::uuid4();
        }

        $hasRequestId = true;
        $requestId    = $request->headers->get(Browser::HEADER_REQUEST_ID);

        if (!Utils::isUuid4((string) $requestId)) {
            $hasRequestId = false;
            $requestId    = Utils::uuid4();
        }

        $ipAddress = $request->getClientIp();
        $userAgent = $request->userAgent();
        $locale    = $request->getPreferredLanguage(Browser::locales());
        $country   = Browser::country();
        $timezone  = Browser::timezone();

        if (Browser::cloudflare()) {
            $ipAddress = $request->header(Browser::HEADER_CF_IP_ADDRESS, $ipAddress);
            $country   = $request->header(Browser::HEADER_CF_COUNTRY, $country);
        }

        Browser::setDeviceId($deviceId);
        Browser::setRequestId($requestId);
        Browser::setIpAddress($ipAddress);
        Browser::setUserAgent($userAgent);
        Browser::setLocale($request->cookie(Browser::COOKIE_LOCALE, $locale));
        Browser::setCountry($request->cookie(Browser::COOKIE_COUNTRY, $country));
        Browser::setTimezone($request->cookie(Browser::COOKIE_TIMEZONE, $timezone));

        /**
         * @var Response $response
         */
        $response = $next($request);

        if (!$hasDeviceId) {
            $response->headers->setCookie(
                new Cookie(Browser::COOKIE_DEVICE_ID, $deviceId, Carbon::now()->addRealSeconds(Browser::COOKIE_TTL), '/', null, true, true, false, Cookie::SAMESITE_NONE)
            );
        }

        $response->headers->set(Browser::HEADER_DEVICE_ID, $deviceId);

        if (!$hasRequestId) {
            $response->headers->set(Browser::HEADER_REQUEST_ID, $requestId);
        }

        return $response;
    }
}
