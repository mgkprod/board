<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootCacheBusting();
        $this->bootSharedProperties();
    }

    /**
     * Boot cache busting.
     *
     * @return void
     */
    protected function bootCacheBusting()
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });
    }

    /**
     * Boot shared properties.
     *
     * @return void
     */
    protected function bootSharedProperties()
    {
        Inertia::share([
            'auth' => function () {
                return optional(
                    auth()->user()
                )->load('permissions');
            },
            'errors' => function () {
                return $this->sharedValidationErrors();
            },
        ]);
    }

    /**
     * Resolve shared validation errors.
     *
     * @return \Illuminate\Contracts\Support\MessageBag|\stdClass
     */
    protected function sharedValidationErrors()
    {
        if ($errors = session('errors')) {
            return $errors->getBag('default');
        }

        return new \stdClass;
    }
}
