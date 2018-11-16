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
 * @package Openium\PlatiniumBundle\Service
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
     * @var string
     */
    private $env;

    /**
     * PlatiniumClientService constructor.
     *
     * @param string $serverUrl
     * @param PlatiniumSignatureService $platiniumSignatureService
     * @param string $env
     */
    public function __construct(string $serverUrl, PlatiniumSignatureService $platiniumSignatureService, string $env)
    {
        $this->platiniumSignatureService = $platiniumSignatureService;
        $this->serverUrl = $serverUrl;
        $this->env = $env;
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
        if ($this->env === 'test') {
            return new PlatiniumPushResponse(1, 'test');
        }
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
            $header = explode(": ", $value);
            if ($header[0] && !isset($header[1])) {
                $headerData['status'] = $header[0];
            } elseif ($header[0] && $header[1]) {
                $headerData[$header[0]] = $header[1];
            }
        }
        return $headerData;
    }
}