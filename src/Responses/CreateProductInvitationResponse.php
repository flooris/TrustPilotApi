<?php


namespace Flooris\Trustpilot\Responses;


class CreateProductInvitationResponse
{

    public $review_link_id;
    public $review_url;

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($source)
    {
        $this->review_link_id = $source->reviewLinkId;
        $this->review_url = $source->reviewUrl;
    }
}