<?php

namespace Egobude\SecretManagement\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Secret extends BaseModel
{
    /**
     * @Flow\Validate(type="StringLength", options={"maximum"=255})
     * @ORM\Column(length=255)
     * @var string
     */
    protected $secret = '';

    /**
     * @Flow\Validate(type="StringLength", options={"maximum"=255})
     * @ORM\Column(length=255, nullable=true)
     * @var string
     */
    protected $description = '';

    /**
     * @Flow\Validate(type="StringLength", options={"maximum"=255})
     * @ORM\Column(length=255)
     * @var string
     */
    protected $linkToken = '';

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $expiryDate;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $expired = false;

    /**
     * @return string
     */
    public function getLinkToken(): string
    {
        return $this->linkToken;
    }

    /**
     * @param string $linkToken
     */
    public function setLinkToken(string $linkToken): void
    {
        $this->linkToken = $linkToken;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret($secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param \DateTime $expiryDate
     */
    public function setExpiryDate($expiryDate): void
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return bool
     */
    public function isExpired():bool
    {
        die('asdasd');

        $currentDateTime = new \DateTime('now');

        return $currentDateTime > $this->expiryDate;
    }

    /**
     * @return bool
     */
    public function getExpired():bool
    {
        return $this->expired;
    }

    /**
     * @param bool $expired
     */
    public function setExpired($expired): void
    {
        $this->expired = $expired;
    }


}