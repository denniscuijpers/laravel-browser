# laravel-browser

### Installation
```angular2html
composer require denniscuijpers/laravel-browser
```

```php
<?php

class Kernel extends HttpKernel
{
    protected $middleware = [
        \DennisCuijpers\Browser\Middleware\BrowserMiddleware::class,
    ];
}
```
