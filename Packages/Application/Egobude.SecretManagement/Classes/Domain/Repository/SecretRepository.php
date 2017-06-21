<?php
namespace Egobude\SecretManagement\Domain\Repository;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class SecretRepository extends Repository
{
    /**
     * @param $linkToken
     * @return object
     * @throws \Exception
     */
    public function findByLinkToken($linkToken)
    {
        $query = $this->createQuery();
        $result = $query->matching(
            $query->logicalAnd(
                $query->equals('linkToken', $linkToken),
                $query->equals('expired', 0)
            )
        )->execute();

        if ($result->count() > 2) {
            throw new \Exception(sprintf('Several entries were found for linkToken %s', $linkToken), 1498034050);
        }

        return $result->getFirst();
    }
}