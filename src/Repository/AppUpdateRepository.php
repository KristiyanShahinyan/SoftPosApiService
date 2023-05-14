<?php

namespace App\Repository;

use Phos\Repository\BaseRepository;

class AppUpdateRepository extends BaseRepository
{
    public function findAllUpdatesWithNewerOrEqualVersion(int $instanceId, int $appVersion)
    {
        return $this->createQueryBuilder('u')
            ->where('u.instance = :instanceId')
            ->andWhere('u.version >= :appVersion')
            ->setParameter('instanceId', $instanceId)
            ->setParameter('appVersion', $appVersion)
            ->orderBy('u.version', 'DESC')
            ->getQuery()
            ->getResult();
    }
}