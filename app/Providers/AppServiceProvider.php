<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::createAssetPathsUsing(fn (string $path): string => '/'.ltrim($path, '/'));

        config([
            'filesystems.disks.public.url' => '/storage',
        ]);

        if (! $this->app->runningInConsole()) {
            $request = request();
            $forwardedProto = strtolower((string) $request->header('X-Forwarded-Proto'));
            $forwardedScheme = strtolower((string) $request->header('X-Forwarded-Scheme'));
            $forwardedHost = trim(explode(',', (string) $request->header('X-Forwarded-Host'))[0]);
            $effectiveHost = strtolower($forwardedHost !== '' ? $forwardedHost : (string) $request->getHost());

            $isTunnelHost = str_contains($effectiveHost, 'devtunnels.ms')
                || str_ends_with($effectiveHost, '.ngrok-free.app')
                || str_ends_with($effectiveHost, '.ngrok.io')
                || str_ends_with($effectiveHost, '.ngrok.app')
                || str_ends_with($effectiveHost, '.trycloudflare.com')
                || str_ends_with($effectiveHost, '.loca.lt');

            $isHttps = $request->isSecure()
                || str_contains($forwardedProto, 'https')
                || str_contains($forwardedScheme, 'https')
                || $isTunnelHost;

            $isLocalHost = in_array($effectiveHost, ['localhost', '127.0.0.1', '::1', '[::1]'], true);

            if ($isHttps) {
                URL::forceScheme('https');
            }

            /*
             * DevTunnel/ngrok dapat meneruskan Host tanpa X-Forwarded-Host,
             * sehingga root URL harus tetap dibangun dari host publik agar
             * route()/asset() tidak menghasilkan http://127.0.0.1.
             */
            if (! $isLocalHost && ($forwardedHost !== '' || $isTunnelHost)) {
                $scheme = $isHttps ? 'https' : $request->getScheme();
                $rootUrl = "{$scheme}://{$effectiveHost}";

                URL::forceRootUrl($rootUrl);
                config(['app.url' => $rootUrl]);
            }
        }
    }
}
