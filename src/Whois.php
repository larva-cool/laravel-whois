<?php

namespace Larva\Whois;

use Illuminate\Support\Facades\Facade;
use Iodev\Whois\Modules\Tld\TldInfo;

/**
 * Whois 查询
 * @method static array lookup(string $domain)
 * @method static string lookupRaw(string $domain)
 * @method static TldInfo lookupInfo(string $domain)
 * @method static string getDomain(string $hostName)
 * @mixin WhoisQuery
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Whois extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return WhoisQuery::class;
    }
}