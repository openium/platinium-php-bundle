<?php

namespace Openium\PlatiniumBundle;

use Openium\PlatiniumBundle\Entity\PlatiniumPushResponse;
use Openium\PlatiniumBundle\Service\PlatiniumSignatureService;

/**
 * Class PlatiniumClientService
 *
 * @package Openium\PlatiniumBundle
 */
class PlatiniumClient
{
    // platinium status code header name
    private const PLATINIUM_STATUS_CODE_HEADER = 'X-Platinium-Status-Code';

    // alternative status code header name (without upper letter)
    private const PLATINIUM_STATUS_CODE_HEADER_ALT = 'x-platinium-status-code';

    public function __construct(
        private readonly string $serverUrl,
        private readonly PlatiniumSignatureService $platiniumSignatureService
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if ($response) {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $result = null;
            if ($httpStatusCode == 200) {
                $responseHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $stringResponseHeader = substr($response, 0, $responseHeaderSize);
                $responseHeaders = $this->parseHttpHeaders($stringResponseHeader);
                if (array_key_exists(self::PLATINIUM_STATUS_CODE_HEADER, $responseHeaders)) {
                    $httpStatusCode = $responseHeaders[self::PLATINIUM_STATUS_CODE_HEADER];
                } elseif (
                    array_key_exists(
                        self::PLATINIUM_STATUS_CODE_HEADER_ALT,
                        $responseHeaders
                    )
                ) {
                    $httpStatusCode = $responseHeaders[self::PLATINIUM_STATUS_CODE_HEADER_ALT];
                }

                $result = substr($response, $responseHeaderSize);
            } else {
                $result = 'HTTP Code : ' . $httpStatusCode;
            }
        } else {
            $result = 'CURL error : ' . curl_error($ch);
            $httpStatusCode = -1;
        }

        curl_close($ch);
        return new PlatiniumPushResponse($httpStatusCode, $result);
    }

    /**
     * parseHttpHeaders
     * transform string headers to ["key" => "value"] array
     *
     * @return array<string, string>
     */
    public function parseHttpHeaders(string $headers): array
    {
        $headers = str_replace("\r", "", $headers);
        $headers = explode("\n", $headers);

        $headerData = [];
        foreach ($headers as $value) {
            $header = explode(":", $value, 2);
            if ($header[0] && !isset($header[1])) {
                $headerData['status'] = trim($header[0]);
            } elseif ($header[0] && isset($header[1])) {
                $headerData[trim($header[0])] = trim($header[1]);
            }
        }

        return $headerData;
    }
}
