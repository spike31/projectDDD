<?php

namespace UserIdentity\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;
use UserIdentity\Domain\Model\EmailAddress;
use UserIdentity\Domain\Model\UserId;

class UserWasRegistered extends AggregateChanged
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var EmailAddress
     */
    private $emailAddress;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $roles;

    public static function withData(UserId $userId, EmailAddress $emailAddress, $password, $roles)
    {
        $event = self::occur(
            $userId->toString(),
            [
                'password' => $password,
                'email' => $emailAddress->toString(),
                'roles' => $roles,
            ]
        );

        $event->userId = $userId;
        $event->username = $emailAddress->toString();
        $event->emailAddress = $emailAddress;
        $event->roles = $roles;

        return $event;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        if ($this->userId === null) {
            $this->userId = UserId::fromString($this->aggregateId());
        }

        return $this->userId;
    }

    public function password()
    {
        return $this->password = $this->payload['password'];
    }

    public function username()
    {
        if ($this->username === null) {
            $this->username = $this->payload['email'];
        }

        return $this->username;
    }

    /**
     * @return EmailAddress
     */
    public function emailAddress()
    {
        if ($this->emailAddress === null) {
            $this->emailAddress = EmailAddress::fromString($this->payload['email']);
        }

        return $this->emailAddress;
    }

    public function roles()
    {
        if ($this->roles === null) {
            $this->roles = $this->payload['roles'];
        }

        return $this->roles;
    }
}