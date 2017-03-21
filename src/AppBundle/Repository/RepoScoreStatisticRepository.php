<?php

namespace AppBundle\Repository;

/**
 * RepoScoreStatistic
 *
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class RepoScoreStatisticRepository extends \Doctrine\ORM\EntityRepository
{
    const RECORD_NUMBERS = 10;
    
    /**
     * Getting last records.
     *
     * @return array
     */
    public function getLastRecords()
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->setMaxResults(self::RECORD_NUMBERS)
            ->orderBy('r.id', 'DESC');

        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }
}
