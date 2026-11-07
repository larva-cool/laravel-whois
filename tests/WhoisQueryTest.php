<?php


namespace Larva\Whois\Tests;

use Larva\Whois\WhoisQuery;

class WhoisQueryTest extends TestCase
{
    public function testParseDomain()
    {
        $domain = 'www.china.com.cn';
        $whois = new WhoisQuery();
        $result = $whois->parseDomain($domain);
        $this->assertTrue($result->registrableDomain()->toString() == 'china.com.cn');
    }

    public function testLookupRaw()
    {
        $domain = 'www.google.com';
        $whois = new WhoisQuery();
        $result = $whois->lookupRaw($domain);
        $this->assertIsString($result);
    }

    public function testLookupInfo()
    {
        $domain = 'www.qq.com';
        $whois = new WhoisQuery();
        $result = $whois->lookupInfo($domain);
        $this->assertIsString($result->getResponse()->text);
    }
}