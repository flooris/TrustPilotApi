<?php

namespace Flooris\Trustpilot;

class InvitationTemplate
{

    public $id;
    public $name;
    public $is_default_template;

    public function __construct($source)
    {
        $this->id = $source->id;
        $this->name = $source->name;
        $this->is_default_template = $source->isDefaultTemplate;
    }

}