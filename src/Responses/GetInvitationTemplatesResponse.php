<?php

namespace Flooris\Trustpilot\Responses;

use Flooris\Trustpilot\InvitationTemplate;

class GetInvitationTemplatesResponse
{

    /** @var InvitationTemplate[] $templates */
    public $templates;

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($source)
    {
        $this->templates = [];

        foreach($source->templates as $template_source) {
            $template = new InvitationTemplate($template_source);

            $this->templates[] = $template;
        }
    }
}