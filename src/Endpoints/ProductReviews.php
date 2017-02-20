<?php

namespace Flooris\Trustpilot\Endpoints;

use Flooris\Trustpilot\TrustpilotApi;
use Flooris\Trustpilot\InvitationProduct;
use Flooris\Trustpilot\InvitationConsumer;
use Flooris\Trustpilot\Responses\GetProductReviewsResponse;
use Flooris\Trustpilot\Responses\CreateProductInvitationResponse;
use Flooris\Trustpilot\Responses\GetPrivateProductReviewsResponse;
use Flooris\Trustpilot\Responses\GetProductReviewSummariesResponse;

class ProductReviews
{

    const API_INVITATIONS_ENDPOINT_PUBLIC = 'https://api.trustpilot.com/v1/product-reviews/business-units/';
    const API_INVITATIONS_ENDPOINT_PRIVATE = 'https://api.trustpilot.com/v1/private/product-reviews/business-units/';

    /** @var TrustpilotApi $client */
    private $client;
    private $business_unit_id;

    public function __construct(TrustpilotApi $client, $business_unit_id)
    {
        $this->client = $client;
        $this->business_unit_id = $business_unit_id;
    }

    protected function getInvitationEndpoint($endpoint, $public = false)
    {
        $base = $public ? self::API_INVITATIONS_ENDPOINT_PUBLIC : self::API_INVITATIONS_ENDPOINT_PRIVATE;

        return sprintf('%s%s%s', $base, $this->business_unit_id, $endpoint);
    }

    /**
     * Get a link that can be sent to the consumer to request reviews.
     *
     * @param InvitationConsumer $consumer
     * @param $reference_id
     * @param $locale
     * @param $redirect_uri
     * @param InvitationProduct[] $products
     * @return CreateProductInvitationResponse|string
     */
    public function createProductReviewInvitationLink(
        InvitationConsumer $consumer,
        $reference_id,
        $locale,
        $redirect_uri,
        $products
    )
    {
        $request = [
            'consumer' => [
                'email' => $consumer->email,
                'name' => $consumer->name
            ],
            'referenceId' => $reference_id,
            'locale' => $locale,
            'redirectUri' => $redirect_uri,
            'products' => [],
        ];

        foreach($products as $product) {
            $request['products'][] = [
                'productUrl' => $product->product_uri,
                'imageUrl' => $product->image_uri,
                'name' => $product->name,
                'sku' => $product->sku,
                'gtin' => $product->gtin,
                'mpn' => $product->mpn,
                'brand' => $product->brand
            ];
        }

        $response = $this->client->post($this->getInvitationEndpoint('/invitation-links'), $request);

        $response = new CreateProductInvitationResponse($response);

        return $response;
    }

    /**
     * Get a list of summaries of product reviews for a business unit.
     * The summary contains information about stars average, distribution and count.
     * Pagination is used to retrieve all results.
     *
     * @param int $page [optional] Default 1 <p>
     * The page to retrieve. If the page number requested is higher than the available number of pages an empty array will be returned.
     * Constraints: The allowed range is minimum: 1, maximum: 2147483647
     * </p>
     * @param int $per_page [optional] Default 1000 <p>
     * The number of summaries to retrieve per page.
     * Constraints: The allowed range is minimum: 1, maximum: 1000
     * </p>
     * @return GetProductReviewSummariesResponse
     */
    public function getProductReviewsSummariesList($page = 1, $per_page = 1000)
    {
        $parameters = http_build_query([
            'page' => (int)$page,
            'perPage' => (int)$per_page,
        ]);

        $endpoint = $this->getInvitationEndpoint('/summaries?') . $parameters;

        $response = $this->client->get($endpoint, false);

        $response = new GetProductReviewSummariesResponse($response);

        return $response;
    }

    /**
     * Get reviews for specified SKU
     *
     * @param $sku
     * @param string $language [optional]
     * @param int $page [optional] Default 1 <p>
     * The page to retrieve. If the page number requested is higher than the available number of pages an empty array will be returned.
     * Constraints: The allowed range is minimum: 1, maximum: 2147483647
     * </p>
     * @param int $per_page [optional] Default 20 <p>
     * The number of reviews to retrieve per page.
     * Constraints: The allowed range is minimum: 1, maximum: 100
     * </p>
     * @return GetProductReviewsResponse|string
     */
    public function getProductReviews($sku, $language = null, $page = 1, $per_page = 20)
    {
        $parameters = [
            'sku' => $sku,
            'page' => (int)$page,
            'perPage' => (int)$per_page,
        ];
        if( $language ) {
            $parameters['language'] = $language;
        }

        $parameters = http_build_query($parameters);

        $endpoint = $this->getInvitationEndpoint('/reviews?', true) . $parameters;

        $response = $this->client->get($endpoint);

        $response = new GetProductReviewsResponse($response);

        return $response;
    }

    /**
     * Get private product reviews
     *
     * Given a list of SKUs return a list of product reviews.
     * This method includes private information such as consumer e-mail and reference id.
     * By default only published reviews are returned.
     * To get reviews with other states, provide a list in the state field.
     * Pagination is used to retrieve all results.
     *
     * @param null|string|array $sku [optional] Default null <p>
     * Filter reviews by product Stock-Keeping Unit (SKU) identifier
     * Default returns reviews for all SKUs
     * </p>
     * @param null|string $language [optional] Default null <p>
     * Filter reviews by Language.
     * Defaults to returning all languages
     * </p>
     * @param string $state [optional] Default published <p>
     * Which reviews to retrieve according to their review state. Default is Published.
     * Constraints: Allowed values are published, unpublished, underModeration, archived
     *
     * Example: published,unpublished
     * </p>
     * @param int $page [optional] Default 1 <p>
     * The page to retrieve. If the page number requested is higher than the available number of pages an empty array will be returned.
     * Constraints: The allowed range is minimum: 1, maximum: 2147483647
     * </p>
     * @param int $per_page [optional] Default 20 <p>
     * The number of reviews to retrieve per page.
     * Constraints: The allowed range is minimum: 1, maximum: 100
     * </p>
     * @return GetPrivateProductReviewsResponse|string
     */
    public function getPrivateProductReviews($sku = null, $language = null, $state = 'published', $page = 1, $per_page = 20)
    {
        $parameters = [
            'state' => $state,
            'page' => $page,
            'perPage' => $per_page
        ];

        if( $language ) {
            $parameters['language'] = $language;
        }

        if( $sku ) {
            if( is_array($sku) ) {
                $sku = implode(',', $sku);
            }
            $parameters['sku'] = $sku;
        }

        $parameters = http_build_query($parameters);

        $endpoint = $this->getInvitationEndpoint('/reviews?') . $parameters;

        $response = $this->client->get($endpoint, false);

        $response = new GetPrivateProductReviewsResponse($response);

        return $response;
    }
}
