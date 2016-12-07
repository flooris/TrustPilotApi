<?php


namespace Flooris\Trustpilot;


class TrustpilotApi
{
    /** @var string AUTH token */
    private $access_token;

    private $api_key;
    private $api_secret;
    private $api_username;
    private $api_password;

    /**
     * TrustpilotApi constructor.
     *
     * @param $api_key
     * @param $api_secret
     * @param $api_username
     * @param $api_password
     */
    public function __construct($api_key, $api_secret, $api_username, $api_password)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->api_username = $api_username;
        $this->api_password = $api_password;
    }

    private function addAuthToken($url, $apikey)
    {
        if( ! $apikey) {
            if( ! $this->access_token) {
                $this->access_token = $this->getAccessToken();
            }

            $endpoint = $url . "?token=". $this->access_token;
        } else {
            $endpoint = $url . "?apikey=". $this->api_key;
        }

        return $endpoint;
    }

    /**
     * Do post on trustpilot API
     *
     * @param $url string The url
     * @param $payload string JSON payload
     * @return string
     */
    public function doPost($url, $payload, $apikey = false)
    {
        $endpoint = $this->addAuthToken($url, $apikey);

        $process = curl_init($endpoint);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($process, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($process);
        if ($return == null)
            return null;

        $retJson = json_decode($return);

        return $retJson;
    }

    /**
     * Do get on trustpilot API
     *
     * @param $url string The url
     * @return string
     */
    public function doGet($url, $apikey = false)
    {
        $endpoint = $this->addAuthToken($url, $apikey);

        $process = curl_init($endpoint);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($process);
        if ($return == null)
            return false;

        return json_decode($return);
    }

    /**
     * Get access token
     *
     * @param bool $first
     * @return string
     * @throws \Exception
     */
    private function getAccessToken($first = true)
    {
        $endpoint   = "https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/accesstoken";

        $process = curl_init($endpoint);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            "Authorization: Basic " . base64_encode($this->api_key . ":" . $this->api_secret),
        ));
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($process, CURLOPT_TIMEOUT, 5);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_FRESH_CONNECT, true);

        $postfields = http_build_query([
            'grant_type' => 'password',
            'username' => $this->api_username,
            'password' => $this->api_password
        ]);

        curl_setopt($process, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($process);
        if ($return == null) {
            if($first) {
                return $this->getAccessToken(false);
            } else {
                throw new \Exception("Access token NULL" . PHP_EOL .
                    "Data:" . PHP_EOL .
                    "Authorization: Basic " . base64_encode($this->api_key . ":" . $this->api_secret) . PHP_EOL .
                    "Post fields: " . $postfields
                );
            }
        }

        curl_close($process);

        $json = json_decode($return);

        $access_token = $json->access_token;

        if ( ! $access_token ) {
            throw new \Exception("Access token NULL" . PHP_EOL .
                "Data:" . PHP_EOL .
                "Authorization: Basic " . base64_encode($this->api_key . ":" . $this->api_secret) . PHP_EOL .
                "Post fields: " . $postfields
            );
        }

        return $access_token;
    }
}
