<?php

namespace Larva\Whois;

use Illuminate\Support\Carbon;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Factory;
use Iodev\Whois\Modules\Tld\TldInfo;
use Pdp\CannotProcessHost;
use Pdp\Domain as WhoisDomain;
use Pdp\ResolvedDomainName;
use Pdp\Rules;

/**
 * Class WhoisQuery
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WhoisQuery
{
    /**
     * 解析域名
     *
     * @param string $host
     * @return ResolvedDomainName
     * @throws IllegalDomainException|\Pdp\CannotProcessHost
     */
    public function parseDomain(string $host): ResolvedDomainName
    {
        if (str_contains($host, '://')) {
            $url = parse_url($host);
            if (isset ($url ['host'])) {
                $host = $url ['host'];
            }
        }
        if ($host && str_contains($host, '.')) {
            $publicSuffixList = Rules::fromPath(__DIR__ . '/../resources/public_suffix_list.dat');
            $domain = WhoisDomain::fromIDNA2008($host);
            return $publicSuffixList->resolve($domain);
        } else {
            throw new IllegalDomainException("Illegal domain name");
        }
    }

    /**
     * 获取域名
     * @param  string  $domain
     * @return string
     * @throws IllegalDomainException
     * @throws \Pdp\CannotProcessHost
     */
    public function getDomain(string $hostName): string
    {
        $domain = $this->parseDomain($hostName);
        return $domain->registrableDomain()->toString();
    }

    /**
     * 查询原始 Whois
     * @param string $domain
     * @return string
     * @throws ConnectionException
     * @throws ServerMismatchException
     * @throws WhoisException
     * @throws IllegalDomainException|\Pdp\CannotProcessHost
     */
    public function lookupRaw(string $domain): string
    {
        $domain = $this->parseDomain($domain);
        $whois = Factory::get()->createWhois();
        return $whois->lookupDomain($domain->registrableDomain()->toString())->text;
    }

    /**
     * 查询 Whois Info
     * @param  string|ResolvedDomainName  $domain
     * @return TldInfo|null
     * @throws ConnectionException
     * @throws IllegalDomainException
     * @throws ServerMismatchException
     * @throws WhoisException
     * @throws CannotProcessHost
     */
    public function lookupInfo(string|ResolvedDomainName $domain): ?TldInfo
    {
        $whois = Factory::get()->createWhois();
        if (!$domain instanceof ResolvedDomainName) {
            $domain = $this->parseDomain($domain);
        }
        return $whois->loadDomainInfo($domain->registrableDomain()->toString());
    }

    /**
     * 查询 Whois
     * @param string $domain 要查询的域名
     * @return array
     * @throws ConnectionException
     * @throws IllegalDomainException
     * @throws ServerMismatchException
     * @throws WhoisException|\Pdp\CannotProcessHost
     */
    public function lookup(string $domain): ?array
    {
        $response = $this->lookupInfo($this->parseDomain($domain));
        if(!$response) {
            return null;
        }
        return [
            'parser_type' => $response->parserType,
            'name' => $response->domainName,
            'registrar' => $response->registrar,
            'owner' => $response->owner,
            'whois_server' => $response->whoisServer,
            'states' => $response->states,
            'name_servers' => $response->nameServers,
            'dnssec' => $response->dnssec,
            'creation_date' => Carbon::createFromTimestamp($response->creationDate),
            'expiration_date' => Carbon::createFromTimestamp($response->expirationDate),
            'updated_date' => Carbon::createFromTimestamp($response->updatedDate),
            'response_raw' => $response->getResponse()->text,
        ];
    }
}