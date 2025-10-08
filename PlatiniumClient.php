<?php

namespace Openium\PlatiniumBundle;

use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class PlatiniumClientService
 *
 * @package Openium\PlatiniumBundle
 */
class PlatiniumClient
{
    private const PLATINIUM_STATUS_CODE_HEADER = 'x-platinium-status-code';

    public function __construct(
        private readonly string $serverUrl,
        private readonly PlatiniumSignatureService $platiniumSignatureService,
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    /**
     * send
     *
     * @param array<string, string> $paramsBag
     */
    public function send(string $path, array $paramsBag): PlatiniumPushResponse
    {
        $requestHeaders = $this->platiniumSignatureService->createServerSignature(
            $path,
            $paramsBag
        );
        $fullURL = $this->serverUrl . $path;
        $params_string = str_replace('+', '%20', http_build_query($paramsBag));
        try {
            $response = $this->httpClient->request(
                method: Request::METHOD_POST,
                url: $fullURL,
                options: [
                    'headers' => $requestHeaders,
                    'body' => $params_string,
                    'verify_peer' => false,
                    'verify_host' => false,
                ]
            );
            $httpStatusCode = $response->getStatusCode();
            if ($httpStatusCode === 200) {
                /** @var array<string, array<string>> $responseHeaders */
                $responseHeaders = $response->getHeaders(false);
                if (isset($responseHeaders[self::PLATINIUM_STATUS_CODE_HEADER][0])) {
                    $httpStatusCode = $responseHeaders[self::PLATINIUM_STATUS_CODE_HEADER][0];
                }
                $result = $response->getContent(false);
            } else {
                $result = 'HTTP Code : ' . $httpStatusCode;
            }
        } catch (TransportExceptionInterface $e) {
            $result = 'Transport error : ' . $e->getMessage();
            $httpStatusCode = -1;
        } catch (ClientExceptionInterface $e) {
            $result = 'Client error : ' . $e->getMessage();
            $httpStatusCode = -1;
        } catch (RedirectionExceptionInterface $e) {
            $result = 'Redirection error : ' . $e->getMessage();
            $httpStatusCode = -1;
        } catch (ServerExceptionInterface $e) {
            $result = 'Server error : ' . $e->getMessage();
            $httpStatusCode = -1;
        }
        return new PlatiniumPushResponse($httpStatusCode, $result);
    }
}
