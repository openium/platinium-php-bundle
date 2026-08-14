<?php

namespace Openium\PlatiniumBundle\Tests;

use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use Openium\PlatiniumBundle\PlatiniumClient;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class PlatiniumClientTest
 *
 * Covers PlatiniumClient's usage of Symfony\Contracts\HttpClient\HttpClientInterface,
 * which is the surface most likely to be affected by a Symfony major version upgrade.
 *
 * @package Openium\PlatiniumBundle\Tests
 */
class PlatiniumClientTest extends TestCase
{
    private const SERVER_URL = 'https://platinium.example.test';
    private const PATH = '/api/server/notify.json';

    private function createClient(HttpClientInterface $httpClient): PlatiniumClient
    {
        $signatureService = new PlatiniumSignatureService('server-id', 'server-key');
        return new PlatiniumClient(self::SERVER_URL, $signatureService, $httpClient);
    }

    private function createResponse(int $statusCode, string $content, array $headers = []): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getHeaders')->with(false)->willReturn($headers);
        $response->method('getContent')->with(false)->willReturn($content);
        return $response;
    }

    public function testSendSendsExpectedRequestToHttpClient(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
            ->method('request')
            ->with(
                Request::METHOD_POST,
                self::SERVER_URL . self::PATH,
                $this->callback(function (array $options): bool {
                    $this->assertArrayHasKey('headers', $options);
                    $this->assertArrayHasKey('x-ws-signature', $options['headers']);
                    $this->assertSame('param1=value1', $options['body']);
                    $this->assertFalse($options['verify_peer']);
                    $this->assertFalse($options['verify_host']);
                    return true;
                })
            )
            ->willReturn($this->createResponse(200, '{}'));

        $client = $this->createClient($httpClient);
        $client->send(self::PATH, ['param1' => 'value1']);
    }

    public function testSendReturnsSuccessResponse(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($this->createResponse(200, '{"result":"ok"}'));

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertInstanceOf(PlatiniumPushResponse::class, $response);
        $this->assertSame(200, $response->getStatus());
        $this->assertSame('{"result":"ok"}', $response->getResult());
    }

    public function testSendUsesPlatiniumStatusCodeHeaderWhenPresent(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn(
            $this->createResponse(200, '{"result":"ok"}', ['x-platinium-status-code' => ['5']])
        );

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(5, $response->getStatus());
        $this->assertSame('{"result":"ok"}', $response->getResult());
    }

    public function testSendWithNonSuccessHttpStatusReturnsHttpCodeMessage(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willReturn($this->createResponse(500, 'Internal Server Error'));

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(500, $response->getStatus());
        $this->assertSame('HTTP Code : 500', $response->getResult());
    }

    public function testSendTransportExceptionIsCaught(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(
            new PlatiniumClientTestFakeTransportException('connection reset')
        );

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(-1, $response->getStatus());
        $this->assertSame('Transport error : connection reset', $response->getResult());
    }

    public function testSendClientExceptionIsCaught(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(
            new PlatiniumClientTestFakeClientException('client error', $this->createMock(ResponseInterface::class))
        );

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(-1, $response->getStatus());
        $this->assertSame('Client error : client error', $response->getResult());
    }

    public function testSendRedirectionExceptionIsCaught(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(
            new PlatiniumClientTestFakeRedirectionException(
                'too many redirects',
                $this->createMock(ResponseInterface::class)
            )
        );

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(-1, $response->getStatus());
        $this->assertSame('Redirection error : too many redirects', $response->getResult());
    }

    public function testSendServerExceptionIsCaught(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(
            new PlatiniumClientTestFakeServerException('server down', $this->createMock(ResponseInterface::class))
        );

        $response = $this->createClient($httpClient)->send(self::PATH, []);

        $this->assertSame(-1, $response->getStatus());
        $this->assertSame('Server error : server down', $response->getResult());
    }
}

/**
 * Minimal fixtures for the HttpClient exception interfaces.
 * Concrete Symfony exception classes require a real ResponseInterface with
 * populated data to build their message, which is unnecessarily heavy here.
 */
class PlatiniumClientTestFakeTransportException extends \Exception implements
    \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
{
}

class PlatiniumClientTestFakeHttpException extends \Exception
{
    public function __construct(string $message, private readonly ResponseInterface $response)
    {
        parent::__construct($message);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

class PlatiniumClientTestFakeClientException extends PlatiniumClientTestFakeHttpException implements
    \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
{
}

class PlatiniumClientTestFakeRedirectionException extends PlatiniumClientTestFakeHttpException implements
    \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
{
}

class PlatiniumClientTestFakeServerException extends PlatiniumClientTestFakeHttpException implements
    \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
{
}
