<?php

namespace Flooris\Trustpilot\Endpoints;

use Flooris\Trustpilot\TrustpilotApi;
use Flooris\Trustpilot\InvitationProduct;
use Flooris\Trustpilot\InvitationConsumer;
use Flooris\Trustpilot\Responses\CreateProductInvitationResponse;

class ProductReviews
{

    const API_INVITATIONS_ENDPOINT = 'https://api.trustpilot.com/v1/private/product-reviews/business-units/';

    /** @var TrustpilotApi $client */
    private $client;
    private $business_unit_id;

    public function __construct(TrustpilotApi $client, $business_unit_id)
    {
        $this->client = $client;
        $this->business_unit_id = $business_unit_id;
    }

    protected function getInvitationEndpoint($endpoint)
    {
        return sprintf('%s%s%s', self::API_INVITATIONS_ENDPOINT, $this->business_unit_id, $endpoint);
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
}
