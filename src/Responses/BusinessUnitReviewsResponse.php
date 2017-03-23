<?php

namespace Flooris\Trustpilot\Responses;

use Flooris\Trustpilot\BusinessUnitReview;

class BusinessUnitReviewsResponse
{
    public $reviews = [];

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($source)
    {
        foreach($source->reviews as $review) {
            $this->reviews[] = new BusinessUnitReview(
                $review->id,
                $review->consumer->displayName,
                $review->consumer->displayLocation,
                $review->stars,
                $review->title,
                $review->text,
                $review->language,
                $review->createdAt,
                (isset($review->companyReply->text) ? $review->companyReply->text : null),
                (isset($review->companyReply->createdAt) ? $review->companyReply->createdAt : null)
            );
        }
    }
}