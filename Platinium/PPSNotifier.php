<?php

namespace Openium\PlatiniumBundle\Platinium;

class PPSNotifier
{
    const ENV_PROD = 0;
    const ENV_DEV = 1;

    private static $server_notify_api_path = '/api/server/notify.json';

    private $apiServerId;
    private $apiServerKey;
    private $apiServerTokenDev;
    private $apiServerTokenProd;
    private $server = 'https://platinium.openium.fr';

    // The following attributes are used for version purpose
    /** @var string $appVersion The version aimed by the push */
    private $appVersion;
    // The operator for the version (=, !=, >, >=, <, <=)
    private $versionOperator;

    // The following attributes are used for geolocation purpose
    /** @var double $latitude */
    private $latitude;
    /** @var double $longitude */
    private $longitude;
    /** @var int $tolerance The tolerance in days (for example, devices seem at least during the last 7 days) */
    private $tolerance;
    /** @var int $radius Radius around latitude and longitude in meters */
    private $radius;

    /**
     * PPSNotifier constructor.
     * @param string $apiServerId Application ID
     * @param string $apiServerKey Server private pey
     * @param string $apiServerTokenDev Developpement environment Token
     * @param string $apiServerTokenProd Production environment Token
     * @param string $server
     */
    function __construct($apiServerId, $apiServerKey, $apiServerTokenDev, $apiServerTokenProd)
    {
        $this->apiServerId = $apiServerId;
        $this->apiServerKey = $apiServerKey;
        $this->apiServerTokenDev = $apiServerTokenDev;
        $this->apiServerTokenProd = $apiServerTokenProd;
    }

    private function http_parse_headers($headers = false) {
        if ($headers === false) {
            return false;
        }

        $headers = str_replace("\r", "", $headers);
        $headers = explode("\n", $headers);
        $headerdata = array();
        foreach ($headers as $value) {
            $header = explode(": ",$value);
            if ($header[0] && !isset($header[1])) {
                $headerdata['status'] = $header[0];
            }
            elseif ($header[0] && $header[1]) {
                $headerdata[$header[0]] = $header[1];
            }
        }

        return $headerdata;
    }

    private function getServerParams($verb, $id, $key, $url, $params = null)
    {
        $timestamp = sprintf('%.0f', round(microtime(1) * 1000));
        if ($params) {
            $paramString = str_replace('+', '%20', http_build_query($params));
            $stringToSign = $verb."\n" . $url . "\n" . $paramString . "\n" . $timestamp . "\n" . $key;
        } else {
            $stringToSign = $verb . "\n" . $url . "\n" . "\n" . $timestamp . "\n" . $key;
        }
        $signature = sha1($stringToSign);

        return array('x-ws-signature: WS-Signature UUID="' . $id . '", Signature="' . $signature . '", Created="' . $timestamp . '"');
    }

    private function makePOSTOn($id, $key, $url, $params)
    {
        $server_params = $this->getServerParams('POST', $id, $key, $url, $params);
        $fullURL = $this->server.$url;
        $params_string = str_replace('+', '%20', http_build_query($params));

        // Open connection
        $ch = curl_init();

        // Set URL & POST data information
        curl_setopt($ch, CURLOPT_URL, $fullURL);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $server_params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        // POST data
        $response = curl_exec($ch);
        if ($response) {
            //var_dump($response);
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $ppsStatusCode = $httpStatusCode;
            $result = NULL;
            if ($httpStatusCode == 200) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($response, 0, $header_size);
                $headers = $this->http_parse_headers($header);
                if (isset($headers['x-platinium-status-code'])) {
                    $ppsStatusCode = $headers['x-platinium-status-code'];
                }
                //var_dump($headers);
                $result = substr($response, $header_size);
            }
        } else {
            $result = 'CURL error : '.curl_error($ch);
            $ppsStatusCode = -1;
        }
        curl_close($ch);

        return new PPSResponse($ppsStatusCode, $result);
    }

    public function notify(PPSNotification $notification, $env = self::ENV_PROD, array $groups = null, array $langs = null)
    {
        $notificationJSON = '[';
        $notificationJSON .= $notification->getJSON();
        $notificationJSON .= ']';

        if ($env == self::ENV_DEV) {
            $token = $this->apiServerTokenDev;
        } else {
            $token = $this->apiServerTokenProd;
        }

        $params = array(
            'api_notify[app]'=> $token,
            'api_notify[params]' => $notificationJSON
            );

        // Groups
        if (count($groups)) {
            $params['api_notify[idsGroups]'] = json_encode($groups);
        }

        // Langs
        if (count($langs)) {
            $params['api_notify[langs]'] = json_encode($langs);
        }

        // Geolocation
        if (!empty($this->latitude)) {
            $params['api_notify[latitude]'] = $this->latitude;
        }
        if (!empty($this->longitude)) {
            $params['api_notify[longitude]'] = $this->longitude;
        }
        if (!empty($this->radius)) {
            $params['api_notify[radius]'] = $this->radius;
        }
        if (!empty($this->tolerance)) {
            $params['api_notify[tolerance]'] = $this->tolerance;
        }

        // Application version
        if (!empty($this->appVersion)) {
            $params['api_notify[appversion]'] = $this->appVersion;
        }
        if (!empty($this->versionOperator)) {
            $params['api_notify[versionOperator]'] = $this->versionOperator;
        }

        return $this->makePOSTOn($this->apiServerId, $this->apiServerKey, self::$server_notify_api_path, $params);
    }

    // Versioned push setters

    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;
    }

    public function setVersionOperator($versionOperator)
    {
        $this->versionOperator = $versionOperator;
    }

    // Geolocated push setters

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    public function setTolerance($tolerance)
    {
        $this->tolerance = $tolerance;
    }

    /**
     * @return string Server URL
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $server Full server URL
     */
    public function setServer($server)
    {
        $this->server = $server;
    }
}

?>
