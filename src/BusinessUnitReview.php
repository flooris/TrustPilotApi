<?php

namespace Flooris\Trustpilot;

class BusinessUnitReview
{

    public $id;
    public $consumer_displayname;
    public $consumer_location;
    public $stars;
    public $title;
    public $text;
    public $language;
    public $created_at;
    public $companyreply_text;
    public $companyreply_created_at;

    public function __construct($id, $consumer_displayname, $consumer_location, $stars, $title, $text, $language, $created_at, $companyreply_text = null, $companyreply_created_at = null)
    {

        $this->id = $id;
        $this->consumer_displayname = $consumer_displayname;
        $this->consumer_location = $consumer_location;
        $this->stars = $stars;
        $this->title = $title;
        $this->text = $text;
        $this->language = $language;
        $this->created_at = $created_at;
        $this->companyreply_text = $companyreply_text;
        $this->companyreply_created_at = $companyreply_created_at;
    }
}