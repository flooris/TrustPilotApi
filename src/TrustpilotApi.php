<?php

namespace Flooris\Trustpilot;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class TrustpilotApi
{

    const API_URI = 'https://api.trustpilot.com/v1/';
    const OAUTH_TOKEN_ENDPOINT = 'oauth/oauth-business-users-for-applications/accesstoken';

    private $api_key;
    private $api_secret;
    private $api_username;
    private $api_password;

    /** @var GuzzleClient $client */
    protected $client;

    /**
     * TrustpilotApi constructor.
     *
     * @param string $api_key               Trustpilot public API key
     * @param string $api_secret            Trustpilot private API secret
     * @param string $api_username          Trustpilot business username
     * @param string $api_password          Trustpilot business password
     * @param array  $extra_client_options  (optional) Extra options passed into the guzzle client instance
     */
    public function __construct($api_key, $api_secret, $api_username, $api_password, $extra_client_options = [])
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->api_username = $api_username;
        $this->api_password = $api_password;

        // Configure guzzle client
        $default_options = [
            'base_uri' => self::API_URI,
        ];

        $client_options = array_merge($default_options, $extra_client_options);

        $this->client = new GuzzleClient($client_options);
    }

    /**
     * Do post on trustpilot API
     *
     * @param $endpoint
     * @param $payload string JSON payload
     * @return string
     */
    public function post($endpoint, $payload)
    {
        // Execute a POST request
        $response = $this->client->post($endpoint, [
            RequestOptions::HEADERS => [
                'apikey' => $this->getAccessToken()
            ],
            RequestOptions::JSON => $payload
        ]);

        // Validate response
        if( 200 == $response->getStatusCode() ) {
            $json = json_decode($response->getBody());

            return $json;
        } else {
            throw new \LogicException('Invalid response, expected 200, got: ' . $response->getStatusCode());
        }
    }

    /**
     * Do get on trustpilot API
     *
     * @param $endpoint string The url
     * @param $public bool Use public authentication method
     * @return string
     */
    public function get($endpoint, $public = true)
    {
        $options = [
            RequestOptions::HEADERS => []
        ];

        if ( ! $public ) {
            $token = $this->getAccessToken();
            $options[RequestOptions::HEADERS]['Authorization'] = "Bearer {$token}";
        } else {
            $options[RequestOptions::HEADERS]['apikey'] = $this->api_key;
        }

        // Execute a GET request
        $response = $this->client->get($endpoint, $options);

        // Validate response
        if( 200 == $response->getStatusCode() ) {
            $json = json_decode($response->getBody());

            return $json;
        } else {
            throw new \LogicException('Invalid response, expected 200, got: ' . $response->getStatusCode());
        }
    }

    /**
     * Get access token
     *
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken()
    {
        // Make a post request to retrieve an access token
        $response = $this->client->post(self::OAUTH_TOKEN_ENDPOINT, [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'password',
                'username' => $this->api_username,
                'password' => $this->api_password
            ],
            RequestOptions::AUTH => [
                $this->api_key,
                $this->api_secret
            ],
            RequestOptions::HEADERS => [
                'Accept' => 'application/json'
            ],
            RequestOptions::HTTP_ERRORS => false
        ]);

        // Validate response
        if( 200 == $response->getStatusCode() ) {
            $json = json_decode($response->getBody());

            if( ! isset($json->access_token) or ! $json->access_token) {
                throw new \LogicException('Invalid json response, no access token found.');
            }

            return $json->access_token;
        } else {
            throw new \LogicException('Invalid response, expected 200, got: ' . $response->getStatusCode());
        }
    }
}
