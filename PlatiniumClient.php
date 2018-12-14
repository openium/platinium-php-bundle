<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

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
    /**
     * @var string
     */
    protected $serverUrl;

    /**
     * @var PlatiniumSignatureService
     */
    private $platiniumSignatureService;

    /**
     * PlatiniumClientService constructor.
     *
     * @param string $serverUrl
     * @param PlatiniumSignatureService $platiniumSignatureService
     */
    public function __construct(string $serverUrl, PlatiniumSignatureService $platiniumSignatureService)
    {
        $this->platiniumSignatureService = $platiniumSignatureService;
        $this->serverUrl = $serverUrl;
    }

    /**
     * send
     *
     * @param string $path
     * @param array $paramsBag
     *
     * @return PlatiniumPushResponse
     */
    public function send(string $path, array $paramsBag): PlatiniumPushResponse
    {
        $requestHeaders = $this->platiniumSignatureService->createServerSignature($path, $paramsBag);
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
                if (array_key_exists('x-platinium-status-code', $responseHeaders)) {
                    $httpStatusCode = $responseHeaders['x-platinium-status-code'];
                }
                $result = substr($response, $responseHeaderSize);
            } else {
                $result = "HTTP Code : {$httpStatusCode}";
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
     * @param string $headers
     *
     * @return array
     */
    public function parseHttpHeaders(string $headers)
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
