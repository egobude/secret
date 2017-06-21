<?php

namespace Egobude\SecretManagement\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Secret extends BaseModel
{
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $secret = '';

    /**
     * @Flow\Validate(type="StringLength", options={"maximum"=255})
     * @ORM\Column(length=255)
     * @var string
     */
    protected $lifetime = 'oneTime';

    /**
     * @var ArrayCollection<SecretShare>
     * @ORM\OneToMany(mappedBy="secret", cascade={"persist", "remove"})
     */
    protected $secretShares;

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
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * setLifetime
     *
     * @param string $lifetime
     */
    public function setLifetime(string $lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * getLifetime
     *
     * @return string
     */
    public function getLifetime(): string
    {
        return $this->lifetime;
    }

    /**
     * getSecretShares
     *
     * @return array
     */
    public function getSecretShares()
    {
        return $this->secretShares;
    }
}
