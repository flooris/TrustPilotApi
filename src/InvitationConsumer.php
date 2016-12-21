<?php


namespace Flooris\Trustpilot;


class InvitationConsumer
{

    public $email;
    public $name;

    /**
     * InvitationConsumer constructor.
     *
     * @param $email
     * @param $name
     */
    public function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }
}