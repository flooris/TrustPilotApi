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
        foreach ($response->productReviews as $product_review) {
            $review = (object)[
                'id'            => $product_review->id,
                'created_at'    => Carbon::parse($product_review->createdAt),
                'stars'         => $product_review->stars,
                'content'       => $product_review->content,
                'product'       => (object)[
                    'sku'  => $product_review->product->sku,
                    'name' => $product_review->product->name,
                ],
                'consumer'      => (object)[
                    'id'    => $product_review->consumer->id,
                    'email' => $product_review->consumer->email,
                    'name'  => $product_review->consumer->name,
                ],
                'language'      => $product_review->language,
                'locale'        => $product_review->locale,
                'state'         => $product_review->state,
                'reference_id'  => $product_review->referenceId,
                'conversion_id' => $product_review->conversationId,
            ];

            $review->attachments = $this->getAttachments($product_review);
            $review->attribute_ratings = $this->getAttributeRatings($product_review);

            $this->reviews[] = $review;
        }
    }

    public function getAttachments($product_review)
    {
        $attachments = [];

        foreach ($product_review->attachments as $review_attachment) {
            $attachment = (object)[
                'state' => $review_attachment->state,
                'id' => $review_attachment->id,
            ];


            $attachment->processed_file = [];

            foreach ($review_attachment->processedFiles as $processed_file) {
                $attachment->processed_file[] = (object)[
                    'dimension' => $processed_file->dimension,
                    'mime_type' => $processed_file->mimeType,
                    'url'       => $processed_file->url,
                ];
            }

            $attachments[] = $attachment;
        }

        return $attachments;
    }

    public function getAttributeRatings($product_review)
    {
        $attribute_ratings = [];

        foreach ($product_review->attributeRatings as $attribute_rating) {
            $rating = (object)[
                'attribute_id' => $attribute_rating->attributeId,
                'attribute_name' => $attribute_rating->attributeName,
                'rating' => $attribute_rating->rating,
            ];


            $attribute_ratings[] = $rating;
        }

        return $attribute_ratings;
    }
}
