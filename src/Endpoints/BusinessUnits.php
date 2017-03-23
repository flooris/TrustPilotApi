<?php

namespace Flooris\Trustpilot\Endpoints;

use Flooris\Trustpilot\TrustpilotApi;
use Flooris\Trustpilot\Responses\BusinessUnitReviewsResponse;

class BusinessUnits
{

    const API_ENDPOINT = 'https://api.trustpilot.com/v1/business-units/';

    public function __construct(TrustpilotApi $client)
    {
        $this->client = $client;
    }

    public function getReviews($business_unit_id, $page = 1, $per_page = 20, $language = '', $orderby = 'createdat.desc')
    {
        $query = [
            'language' => $language,
            'page' => $page,
            'perPage' => $per_page,
            'orderBy' => $orderby,
        ];

        $response = $this->client->get(self::API_ENDPOINT . $business_unit_id . '/reviews?' . http_build_query($query));

        $response = new BusinessUnitReviewsResponse($response);

        dump($response);
        die;

        return $response;
    }

    protected function getInvitationEndpoint($endpoint)
    {

    }
}