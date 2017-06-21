<?php
namespace Egobude\SecretManagement\Command;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Egobude\SecretManagement\Domain\Model\Secret;
use Egobude\SecretManagement\Domain\Model\SecretShare;
use Egobude\SecretManagement\Domain\Repository\SecretRepository;
use Egobude\SecretManagement\Domain\Repository\SecretShareRepository;
use Neos\Eel\Exception;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Utility\Algorithms;

/**
 * @Flow\Scope("singleton")
 */
class SecretManagementCommandController extends CommandController
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
     * sendEmailNotification
     */
    public function sendEmailNotificationCommand(): void
    {
        $secrets = $this->secretRepository->findValidSecrets();

        $transport = \Swift_SmtpTransport::newInstance(
            "mailcatcher", 1025
        );

        foreach ($secrets as $secret) {
            foreach ($secret->getSecretShares() as $secretShare) {
                if (!$secretShare->isNotified()) {
                    $message = \Swift_Message::newInstance();
                    $message->setTo(array(
                        $secretShare->getEmailAddress() => $secretShare->getEmailAddress(),
                    ));
                    $message->setSubject(
                        $secret->getSecret()
                    );
                    $message->setBody($secret->getLinkToken());
                    $message->setFrom("secret@localhost", "SecretManagement");

                    $mailer = \Swift_Mailer::newInstance($transport);
                    $mailer->send($message, $failedRecipients);

                    $secretShare->setNotified(true);
                    $this->secretShareRepository->update($secretShare);
                }
            }
        }
    }

    /**
     * expireSecretsCommand
     */
    public function expireSecretsCommand(): void
    {
        $currentDate = new \DateTime('now');
        $secrets = $this->secretRepository->findAll();

        foreach ($secrets as $secret) {
            try {
                $expiryDate = $secret->getExpiryDate();

                if ($expiryDate < $currentDate) {
                    $secret->setExpired(true);
                }
                $this->secretRepository->update($secret);
                $this->outputLine('Updated secret ' . $secret->getLinkToken());
            } catch (\Exception $e) {
                $this->outputLine($e);
            }
        }

        $this->outputLine('Done');
    }

    /**
     * importSecretsCommand
     *
     * @param int $amount
     * @throws \Exception
     */
    public function importSecretsCommand(int $amount = 10): void
    {
        try {
            $key = Key::loadFromAsciiSafeString($this->encryptionKey);
        } catch (\Exception $e) {
            throw new \Exception('An error while loading encryptionKey', 1498049529);
        }

        $this->secretRepository->removeAll();
        $this->secretShareRepository->removeAll();

        for ($i = 0; $i < $amount; ++$i) {
            try {
                $currentDateTime = new \DateTime('now');

                $string = Crypto::encrypt('Yey, this is a secret.', $key);

                $secret = new Secret();
                $secret->setSecret($string);
                $secret->setDescription('This is a description for the secret ...');
                $secret->setExpiryDate($currentDateTime->add(new \DateInterval('PT10M')));
                $secret->setLinkToken(Algorithms::generateRandomToken(35));

                $secretShare1 = new SecretShare();
                $secretShare1->setSecret($secret);
                $secretShare1->setEmailAddress(md5(microtime() . $i). '@egobude.de');

                $secretShare2 = new SecretShare();
                $secretShare2->setSecret($secret);
                $secretShare2->setEmailAddress(md5(microtime() . $i). '@egobude.de');

                $secretShare3 = new SecretShare();
                $secretShare3->setSecret($secret);
                $secretShare3->setEmailAddress(md5(microtime() . $i). '@egobude.de');

                $this->secretRepository->add($secret);
                $this->secretShareRepository->add($secretShare1);
                $this->secretShareRepository->add($secretShare2);
                $this->secretShareRepository->add($secretShare3);
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
    }
}
