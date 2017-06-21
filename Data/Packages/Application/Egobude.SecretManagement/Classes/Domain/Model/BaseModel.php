<?php
namespace Egobude\SecretManagement\Domain\Model;

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * BaseModel
 *
 * @ORM\HasLifecycleCallbacks
 */
class BaseModel
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * getCreated
     *
     * @return \DateTime
     */
    public function getCreated() : \DateTime
    {
        return $this->created;
    }

    /**
     * getUpdated
     *
     * @return \DateTime
     */
    public function getUpdated() : \DateTime
    {
        return $this->updated;
    }

    /**
     * doPrePersist
     *
     * @ORM\PrePersist
     * @return void
     */
    public function doPrePersist()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * doPreUpdate
     *
     * @ORM\PreUpdate
     * @return void
     */
    public function doPreUpdate()
    {
        $this->updated = new \DateTime();
    }
}
