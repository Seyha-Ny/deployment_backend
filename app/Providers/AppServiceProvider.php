<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            $this->app['request']->server->set('HTTPS', 'on');
            $this->app['url']->forceScheme('https');
        }

        Request::macro('isSecure', function () {
            return $this->server('HTTPS') === 'on'
                || $this->server('HTTP_X_FORWARDED_PROTO') === 'https'
                || $this->server('HTTP_X_FORWARDED_SSL') === 'on';
        });
    }
}
