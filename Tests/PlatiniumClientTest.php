<?php

namespace Openium\PlatiniumBundle\Tests;

use Openium\PlatiniumBundle\PlatiniumClient;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatiniumClientTest
 *
 * @package Openium\PlatiniumBundle\Tests
 */
class PlatiniumClientTest extends TestCase
{
    private function getMockClient(): PlatiniumClient
    {
        $signatureService = new PlatiniumSignatureService('MockedServerId', 'MockedServerKey');
        return new PlatiniumClient('https://platinium-dev.openium.fr', $signatureService);
    }

    public function testParseHttpHeadersWithRightHeaders(): void
    {
        $headers = <<<EOT
HTTP/1.1 200 OK
Date: Mon, 19 Nov 2018 10:08:43 GMT
Server: Apache/2.4.10 (Debian) PHP/7.1.17-1+0~20180505045956.17+jessie~1.gbpde69c6 OpenSSL/1.0.1k
X-Powered-By: PHP/7.1.17-1+0~20180505045956.17+jessie~1.gbpde69c6
Set-Cookie: PHPSESSID=298956d5991cff4fa3881b28f4026774; path=/
Expires: Thu, 19 Nov 1981 08:52:00 GMT
Pragma: no-cache
X-Platinium-Status-Code: 0
Cache-Control: no-cache, private
Content-Length: 14
Content-Type: application/json
EOT;

        $client = $this->getMockClient();
        $result = $client->parseHttpHeaders($headers);
        $this->assertEquals(11, count($result));
        $this->assertEquals(
            [
                "status" => "HTTP/1.1 200 OK",
                "Date" => "Mon, 19 Nov 2018 10:08:43 GMT",
                "Server" => "Apache/2.4.10 (Debian) PHP/7.1.17-1+0~20180505045956.17+jessie~1.gbpde69c6 OpenSSL/1.0.1k",
                "X-Powered-By" => "PHP/7.1.17-1+0~20180505045956.17+jessie~1.gbpde69c6",
                "Set-Cookie" => "PHPSESSID=298956d5991cff4fa3881b28f4026774; path=/",
                "Expires" => "Thu, 19 Nov 1981 08:52:00 GMT",
                "Pragma" => "no-cache",
                "X-Platinium-Status-Code" => "0",
                "Cache-Control" => "no-cache, private",
                "Content-Length" => "14",
                "Content-Type" => "application/json",
            ],
            $result
        );
    }
}
