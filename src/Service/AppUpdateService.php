<?php

namespace App\Service;

use App\Entity\AppUpdate;
use App\EntityManager\AppUpdateManager;
use DateTime;
use LogicException;

class AppUpdateService
{
    private AppUpdateManager $updateManager;

    public function __construct(AppUpdateManager $updateManager)
    {
        $this->updateManager = $updateManager;
    }

    public function getUpdate(int $instanceId, int $appVersion): AppUpdate
    {
	    
        $updates = $this->updateManager->repository->findAllUpdatesWithNewerOrEqualVersion($instanceId, $appVersion);
        if (count($updates) === 0) {
            throw new LogicException(sprintf('It is not possible to have version %d, which is higher than the current.', $appVersion));
        }

        /**
         * @var AppUpdate $latestUpdate
         *
         * The updates are ordered by the version in descending way
         */
        $latestUpdate = current($updates);

        // Early return the latest which is force
        if ($this->isForce($latestUpdate)) {
            $latestUpdate->setForce(true);
            return $latestUpdate;
        }

        // Check if there are any old updates which have force
        foreach ($updates as $update) {
            if ($this->isForce($update)) {
                // This is a bit of a strange logic, because the latestUpdate is not forced,
                // but some older one is. However, we do not return the forced one, but the latest
                $latestUpdate->setForce(true);
                return $latestUpdate;
            }
        }

        // There are no force updates, so return the latest as is
        return $latestUpdate;
    }

    private function isForce(AppUpdate $appUpdate): bool
    {
        if ($appUpdate->getForce() === true) {
            return true;
        }

        $lastDateBeforeForce = $appUpdate->getLastDateBeforeForce();
        if ($lastDateBeforeForce === null) {
            return false;
        }

        $dateTimeInterval = $lastDateBeforeForce->diff((new DateTime()));

        // Check if the last date before force is in the past (0) or in the future (1)
        return $dateTimeInterval->invert === 0;
    }
}
