<?php
/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Service
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Service;

/**
 * Class PlatiniumSignatureService
 *
 * @package Openium\PlatiniumBundle\Service
 */
class PlatiniumSignatureService
{
    protected const HTTP_VERB = 'POST';

    /**
     * @var string
     */
    protected $apiServerId;

    /**
     * @var string
     */
    protected $apiServerKey;

    /**
     * PlatiniumSignatureService constructor.
     */
    public function __construct(string $apiServerId, string $apiServerKey)
    {
        $this->apiServerId = $apiServerId;
        $this->apiServerKey = $apiServerKey;
    }

    /**
     * createServerSignature
     * Create a valid signature for a url and params
     *
     * @param string $url
     * @param array<string, mixed> $params
     *
     * @return string[]
     */
    public function createServerSignature(string $url, array $params = []): array
    {
        $timestamp = strval(round(microtime(true) * 1000));
        $paramString = ($params === [])
            ? ''
            : str_replace('+', '%20', http_build_query($params));
        $stringToSign = sprintf(
            "%s\n%s\n%s\n%s\n%s",
            self::HTTP_VERB,
            $url,
            $paramString,
            $timestamp,
            $this->apiServerKey
        );
        $signature = sha1($stringToSign);
        $header = sprintf(
            'x-ws-signature: WS-Signature UUID="%s", Signature="%s", Created="%s"',
            $this->apiServerId,
            $signature,
            $timestamp
        );
        return [$header];
    }
}
