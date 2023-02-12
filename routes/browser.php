<?php

declare(strict_types=1);

use DennisCuijpers\Browser\Http\Controllers\BrowserController;
use Illuminate\Support\Facades\Route;

Route::get('browser/locale/{locale}', [BrowserController::class, 'locale'])->name('browser.locale');
Route::get('browser/country/{country}', [BrowserController::class, 'country'])->name('browser.country');
Route::get('browser/timezone/{timezone}', [BrowserController::class, 'timezone'])->name('browser.timezone')->where('timezone', '(.*)');
