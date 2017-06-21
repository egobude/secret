<?php
namespace Egobude\SecretManagement\Domain\Service;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Key;
use Egobude\SecretManagement\Domain\Model\Secret;
use Egobude\SecretManagement\Domain\Repository\SecretRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Utility\Algorithms;

/**
 * SecretService
 */
class SecretService extends ActionController
{
    /**
     * @Flow\InjectConfiguration(path="encryptionKey")
     * @var string
     */
    protected $encryptionKey;

    /**
     * @Flow\Inject
     * @var SecretRepository
     */
    protected $secretRepository;

    /**
     * createSecret
     *
     * @param string $decryptedSecret
     * @param string $lifetime
     * @return Secret
     * @throws Exception
     */
    public function createSecret(string $decryptedSecret, string $lifetime): Secret
    {
        try {
            $encryptionKey = Key::loadFromAsciiSafeString($this->encryptionKey);
            $encryptedSecret = Crypto::encrypt($decryptedSecret, $encryptionKey);

            $secret = new Secret();
            $secret->setSecret($encryptedSecret);
            $secret->setLifetime($lifetime);

            return $secret;
        } catch (CryptoException $e) {
            throw new Exception('Secret could no be created', 1498196933);
        }
    }

    /**
     * decipherString
     *
     * @param Secret $secret
     * @return string
     * @throws Exception
     */
    public function decipherString(Secret $secret): string
    {
        try {
            $key = Key::loadFromAsciiSafeString($this->encryptionKey);
            return Crypto::decrypt($secret->getSecret(), $key);
        } catch (CryptoException $e) {
            throw new Exception('Secret could no be created', 1498196933);
        }
    }
}
