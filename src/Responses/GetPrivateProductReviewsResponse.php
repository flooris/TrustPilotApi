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

            if ($product_review->attachments) {
                $review->attachments = $this->getAttachments($product_review);
            } else {
                $review->attachments = [];
            }

            $this->reviews[] = $review;
        }
    }

    public function getAttachments($product_review)
    {
        $attachments = [];

        foreach ($product_review->attachments as $review_attachment) {
            $attachment = (object)[
                'state' => $review_attachment->state,
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
}
