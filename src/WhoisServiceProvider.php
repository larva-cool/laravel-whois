<?php

namespace Larva\Whois;

use Illuminate\Support\ServiceProvider;

/**
 * Whois 服务提供者
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WhoisServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(WhoisQuery::class, function () {
            return new WhoisQuery();
        });
    }
}