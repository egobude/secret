<?php

namespace Egobude\SecretManagement\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class SecretShare extends BaseModel
{
    /**
     * @Flow\Validate(type="StringLength", options={"maximum"=255})
     * @ORM\Column(length=255)
     * @var string
     */
    protected $emailAddress = '';

    /**
     * @var Secret
     * @ORM\Column(nullable=true)
     * @ORM\ManyToOne(inversedBy="secretShares")
     */
    protected $secret;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $notified = false;

    /**
     * @return bool
     */
    public function isNotified(): bool
    {
        return $this->notified;
    }

    /**
     * @param bool $notified
     */
    public function setNotified(bool $notified): void
    {
        $this->notified = $notified;
    }

    /**
     * @return Secret
     */
    public function getSecret(): Secret
    {
        return $this->secret;
    }

    /**
     * @param Secret $secret
     */
    public function setSecret(Secret $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }
}
