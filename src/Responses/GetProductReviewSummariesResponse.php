<?php

namespace Flooris\Trustpilot\Responses;

class GetProductReviewSummariesResponse
{
    public $summaries = [];

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($raw_response)
    {
        foreach($raw_response->summaries as $summary) {
            $this->summaries[] = (object)[
                'sku' => $summary->sku,
                'stars_average' => $summary->starsAverage,
                'number_of_reviews' => (object)[
                    'total'      => $summary->numberOfReviews->total,
                    'one_star'   => $summary->numberOfReviews->oneStar,
                    'two_stars'   => $summary->numberOfReviews->twoStars,
                    'three_stars' => $summary->numberOfReviews->threeStars,
                    'four_stars'  => $summary->numberOfReviews->fourStars,
                    'five_stars'  => $summary->numberOfReviews->fiveStars,
                ],
            ];
        }
    }
}
