<?php

namespace Flooris\Trustpilot\Responses;

use Carbon\Carbon;

class GetPrivateProductReviewsResponse
{

    public $reviews;

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($response)
    {
        foreach($response->productReviews as $product_review) {
            $this->reviews[] = (object)[
                'id' => $product_review->id,
                'created_at' => Carbon::parse($product_review->createdAt),
                'stars' => $product_review->stars,
                'content' => $product_review->content,
                'product' => (object)[
                    'sku' => $product_review->product->sku,
                    'name' => $product_review->product->name,
                ],
                'consumer' => (object)[
                    'id' => $product_review->consumer->id,
                    'email' => $product_review->consumer->email,
                    'name' => $product_review->consumer->name,
                ],
                'language' => $product_review->language,
                'locale' => $product_review->locale,
                'state' => $product_review->state,
                'reference_id' => $product_review->referenceId,
                'conversion_id' => $product_review->conversationId,
            ];
        }
    }
}
