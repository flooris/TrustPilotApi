<?php


namespace Flooris\Trustpilot\Responses;


use Carbon\Carbon;

class GetProductReviewsResponse
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
                'created_at' => Carbon::parse($product_review->createdAt),
                'stars' => $product_review->stars,
                'content' => $product_review->content,
                'consumer' => (object)[
                    'id' => $product_review->consumer->id,
                    'display_name' => $product_review->consumer->displayName,
                ],
                'language' => $product_review->language,
            ];
        }
    }
}
