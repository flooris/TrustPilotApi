<?php

namespace Flooris\Trustpilot\Endpoints;

use Carbon\Carbon;
use Flooris\Trustpilot\TrustpilotApi;
use Flooris\Trustpilot\Responses\CreateInvitationResponse;
use Flooris\Trustpilot\Responses\GetInvitationTemplatesResponse;

class Invitation
{

    const API_INVITATIONS_ENDPOINT = 'https://invitations-api.trustpilot.com/v1/private/business-units/';

    /** @var TrustpilotApi $client */
    private $client;
    private $business_unit_id;

    public function __construct(TrustpilotApi $client, $business_unit_id)
    {
        $this->client = $client;
        $this->business_unit_id = $business_unit_id;
    }

    protected function getInvitationEndpoint($endpoint)
    {
        return sprintf('%s%s%s', self::API_INVITATIONS_ENDPOINT, $this->business_unit_id, $endpoint);
    }

    /**
     * Create new invitation
     * This API endpoint triggers an email invitation.
     * Use the redirect parameter to pass in a product review invitation link.
     *
     * @param string $email Recipient email address
     * @param string $name Recipient name
     * @param string $reference Message reference
     * @param string $template Template ID, can be obtained using the getInvitationTemplates() method
     * @param string $locale Locale that is used in sending the invitation
     * @param string $sender_email Email that is used as the sender of the invitation message
     * @param string $sender_name Name that is used as the sender of the invitation message
     * @param string $reply_to Email that is used when an invited customer sends a reply to the invitation message
     * @param Carbon $send_time Preferred date-time for sending the invitation message. Must be in UTC
     * @param array $tags Array of tags that will be applied to the invitation
     * @param string $redirect URI where the customer will be redirected to after completing the review
     * @return CreateInvitationResponse
     */
    public function createInvitation(
        $email,
        $name,
        $reference,
        $template,
        $locale,
        $sender_email,
        $sender_name,
        $reply_to,
        Carbon $send_time,
        $tags,
        $redirect
    )
    {
        $request = [
            'recipientEmail' => $email,
            'recipientName' => $name,
            'referenceId' => $reference,
            'templateId' => $template,
            'locale' => $locale,
            'senderEmail' => $sender_email,
            'senderName' => $sender_name,
            'replyTo' => $reply_to,
            'preferredSendTime' => $send_time->toIso8601String(),
            'tags' => $tags,
            'redirectUri' => $redirect
        ];

        $response = $this->client->post($this->getInvitationEndpoint('/invitations'), json_encode($request));

        $response = new CreateInvitationResponse($response);

        return $response;
    }

    /**
     * Get a list of invitation templates
     * Returns a list of ID and Names of the templates available to be used in invitations.
     * Includes both standard and custom templates.
     *
     * @return GetInvitationTemplatesResponse
     */
    public function getInvitationTemplates()
    {
        $response = $this->client->get($this->getInvitationEndpoint('/templates'), false);

        $response = new GetInvitationTemplatesResponse($response);

        return $response;
    }
}
