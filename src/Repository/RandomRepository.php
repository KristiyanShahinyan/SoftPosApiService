<?php

namespace App\Repository;

use App\Entity\Random;
use Phos\Repository\BaseRepository;

class RandomRepository extends BaseRepository
{
    public function findOneNotExpired(string $base64EncodedRandom): ?Random
    {
        return $this->createQueryBuilder('r')
            ->where('r.base64EncodedRandom = :base64EncodedRandom')
            ->andWhere('r.expiresOn > :dateTimeNow')
            ->setParameter('base64EncodedRandom', $base64EncodedRandom)
            ->setParameter('dateTimeNow', new \DateTime())
            ->getQuery()
            ->getOneOrNullResult();
    }
}