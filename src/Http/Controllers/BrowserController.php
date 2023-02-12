<?php

declare(strict_types=1);

namespace DennisCuijpers\Browser\Http\Controllers;

use Carbon\Carbon;
use DennisCuijpers\Browser\Browser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Cookie;

class BrowserController extends Controller
{
    public function locale(string $locale): RedirectResponse
    {
        Browser::setLocale($locale);

        return $this->redirect(Browser::COOKIE_LOCALE, Browser::locale());
    }

    public function country(string $country): RedirectResponse
    {
        Browser::setCountry($country);

        return $this->redirect(Browser::COOKIE_COUNTRY, Browser::country());
    }

    public function timezone(string $timezone): RedirectResponse
    {
        Browser::setTimezone($timezone);

        return $this->redirect(Browser::COOKIE_TIMEZONE, Browser::timezone());
    }

    private function redirect(string $key, string $value): RedirectResponse
    {
        return redirect()->back()->withCookie(
            new Cookie($key, $value, Carbon::now()->addRealSeconds(Browser::COOKIE_TTL))
        );
    }
}
