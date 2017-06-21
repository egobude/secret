<?php
namespace Egobude\SecretManagement\Controller;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Egobude\SecretManagement\Domain\Job\DeleteSecretJob;
use Egobude\SecretManagement\Domain\Model\Secret;
use Egobude\SecretManagement\Domain\Repository\SecretRepository;
use Egobude\SecretManagement\Domain\Repository\SecretShareRepository;
use Egobude\SecretManagement\Domain\Service\SecretService;
use Egobude\SecretManagement\Exception\InvalidSecretException;
use Flowpack\JobQueue\Common\Job\JobManager;
use Neos\Error\Messages as Error;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Controller\ActionController;

/**
 * SecretController
 */
class SecretController extends ActionController
{
    /**
     * @Flow\Inject
     * @var SecretRepository
     */
    protected $secretRepository;

    /**
     * @Flow\Inject
     * @var SecretShareRepository
     */
    protected $secretShareRepository;

    /**
     * @Flow\InjectConfiguration(path="encryptionKey")
     * @var string
     */
    protected $encryptionKey;

    /**
     * @Flow\Inject
     * @var SecretService
     */
    protected $secretService;

    /**
     * @Flow\Inject
     * @var DeleteSecretJob
     */
    protected $deleteSecretJob;

    /**
     * @Flow\Inject
     * @var JobManager
     */
    protected $jobManager;

    /**
     * indexAction
     *
     * @return void
     */
    public function indexAction(): void
    {
        $this->view->assign('secrets', $this->secretRepository->findAll());
    }

    /**
     * showAction
     *
     * @param Secret $secret
     * @throws InvalidSecretException
     * @throws \Exception
     */
    public function showAction(Secret $secret): void
    {
        if ($secret instanceof Secret) {
            try {
                $this->view->assign('secretData', $this->secretService->decipherString($secret));
                if ($secret->getLifetime() === 'oneTime') {
                    $this->persistenceManager->remove($secret);
                    $this->persistenceManager->persistAll();
                }
            } catch (\Exception $e) {
                throw new \Exception('An error while loading encryptionKey', 1498049529);
            }
        } else {
            throw new InvalidSecretException('Sorry mate, the secret is not valid.', 1498045667);
        }
    }

    /**
     * linkAction
     *
     * @param Secret $secret
     * @throws Exception
     */
    public function linkAction(Secret $secret): void
    {
        try {
            $this->uriBuilder->setCreateAbsoluteUri(true);
            $uri = $this->uriBuilder->uriFor('show', array('secret' => $secret), 'Secret');
            $this->view->assign('secret', $secret);
            $this->view->assign('uri', $uri);
        } catch (Exception $e) {
            throw new Exception('An error occurred while generating secret uri.', 1498206885);
        }
    }

    /**
     * addAction
     */
    public function addAction(): void
    {
    }

    /**
     * createAction
     *
     * @param string $decryptedString
     * @param string $lifetime
     * @throws Exception
     */
    public function createAction(string $decryptedString, string $lifetime): void
    {
        if (empty(trim($decryptedString))) {
            $this->addFlashMessage('Secret cannot be empty', 'Notice', Error\Message::SEVERITY_ERROR);
            $this->redirect('add');
        }

        $secret = $this->secretService->createSecret($decryptedString, $lifetime);
        $this->secretRepository->add($secret);
        $this->persistenceManager->persistAll();
        $this->redirect('link', null, null, array('secret' => $secret));
    }

    /**
     * deleteAction
     *
     * @param Secret $secret
     */
    public function deleteAction(Secret $secret): void
    {
        $this->secretRepository->remove($secret);
        $this->persistenceManager->persistAll();
        $this->redirect('index');
    }
}
