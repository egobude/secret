<?php
namespace Egobude\SecretManagement\Domain\Job;

use Egobude\SecretManagement\Domain\Repository\SecretRepository;
use Neos\Flow\Annotations as Flow;
use Egobude\SecretManagement\Domain\Model\Secret;
use Flowpack\JobQueue\Common\Job\JobInterface;
use Flowpack\JobQueue\Common\Queue\Message;
use Flowpack\JobQueue\Common\Queue\QueueInterface;
use Neos\Flow\Persistence\PersistenceManagerInterface;

/**
 * DeleteSecretJob
 */
class DeleteSecretJob implements JobInterface
{

    /**
     * The persistence object identifier
     *
     * @var string
     */
    protected $persistenceObjectIdentifier;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var SecretRepository
     */
    protected $secretRepository;

    /**
     * @param string $persistenceObjectIdentifier
     */
    public function __construct(string $persistenceObjectIdentifier = '')
    {
        $this->persistenceObjectIdentifier = $persistenceObjectIdentifier;
    }

    /**
     * execute
     *
     * @param QueueInterface $queue
     * @param Message $message
     * @return bool
     */
    public function execute(QueueInterface $queue, Message $message): bool
    {
        $secret = $this->persistenceManager->getObjectByIdentifier($this->persistenceObjectIdentifier);

        $this->secretRepository->remove($secret);
        $this->persistenceManager->persistAll();
        return true;
    }

    /**
     * getIdentifier
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'DeleteSecretJob';
    }

    /**
     * getLabel
     *
     * @return string
     */
    public function getLabel(): string
    {
        return sprintf('Delete Object %s', $this->persistenceObjectIdentifier);
    }
}
