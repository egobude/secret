<?php
namespace Egobude\SecretManagement\Controller;

use Egobude\SecretManagement\Domain\Model\Secret;
use Egobude\SecretManagement\Domain\Repository\SecretRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Utility\Algorithms;

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
     * indexAction
     *
     * @return void
     */
    public function indexAction()
    {
        try {
            /*$currentDateTime = new \DateTime('now');

            $secret = new Secret();
            $secret->setSecret(time());
            $secret->setDescription('This is a secret');
            $secret->setExpiryDate($currentDateTime->add(new \DateInterval('PT10S')));
            $secret->setLinkToken(Algorithms::generateRandomToken(35));

            $this->secretRepository->add($secret);
            $this->persistenceManager->persistAll();*/
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        $this->view->assign('secrets', $this->secretRepository->findAll());
    }

    /**
     * showAction
     *
     * @param string $linkToken
     */
    public function showAction($linkToken)
    {
        $this->view->assign('secret', $this->secretRepository->findByLinkToken($linkToken));
    }
}
