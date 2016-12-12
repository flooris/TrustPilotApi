<?php

namespace Flooris\Trustpilot\Responses;

use Carbon\Carbon;

class CreateInvitationResponse
{

    public $id;
    public $business_unit_id;
    public $business_user_id;
    public $recipient_name;
    public $recipient_email;
    public $reference_id;
    public $template_id;
    public $locale;
    public $sender_email;
    public $sender_name;
    public $reply_to;
    public $created_time;
    public $preferred_send_time;
    public $sent_time;
    public $tags;
    public $redirect_uri;
    public $status;
    public $source;

    public function __construct($response)
    {
        $this->hydrate($response);
    }

    protected function hydrate($source)
    {
        $this->id = $source->id;
        $this->business_unit_id = $source->businessUnitId;
        $this->business_user_id = $source->businessUserId;
        $this->recipient_name = $source->recipient->name;
        $this->recipient_email = $source->recipient->email;
        $this->reference_id = $source->referenceId;
        $this->template_id = $source->templateId;
        $this->locale = $source->locale;
        $this->sender_email = $source->sender->email;
        $this->sender_name = $source->sender->name;
        $this->reply_to = $source->replyTo;
        $this->created_time = Carbon::createFromFormat(DATE_ISO8601, $source->createdTime);
        $this->preferred_send_time = Carbon::createFromFormat(DATE_ISO8601, $source->preferredSendTime);
        $this->sent_time = Carbon::createFromFormat(DATE_ISO8601, $source->sentTime);
        $this->tags = $source->tags;
        $this->redirect_uri = $source->redirectUri;
        $this->status = $source->status;
        $this->source = $source->source;
    }

}