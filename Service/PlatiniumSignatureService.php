<?php

namespace Openium\PlatiniumBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlatiniumSignatureService
 *
 * @package Openium\PlatiniumBundle\Service
 */
class PlatiniumSignatureService
{
    public function __construct(
        private readonly string $apiServerId,
        private readonly string $apiServerKey
    ) {
    }

    /**
     * createServerSignature
     * Create a valid signature for a url and params
     *
     * @param array<string, mixed> $params
     *
     * @return array<string, string>
     */
    public function createServerSignature(string $url, array $params = []): array
    {
        $timestamp = (string) (int) (microtime(true) * 1000);
        $paramString = ($params === [])
            ? ''
            : str_replace('+', '%20', http_build_query($params));
        $stringToSign = sprintf(
            "%s\n%s\n%s\n%s\n%s",
            Request::METHOD_POST,
            $url,
            $paramString,
            $timestamp,
            $this->apiServerKey
        );
        $signature = sha1($stringToSign);
        return [
            'x-ws-signature' => sprintf(
                'WS-Signature UUID="%s", Signature="%s", Created="%s"',
                $this->apiServerId,
                $signature,
                $timestamp
            )
        ];
    }
}
