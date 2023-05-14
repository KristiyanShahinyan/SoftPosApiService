<?php

namespace App\Service;

use App\Entity\Random;
use App\Helper\CryptoHelper;
use App\Helper\Utils;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RandomService
{
    private EntityManagerInterface $em;

    private CryptoHelper $cryptoHelper;

    public function __construct(EntityManagerInterface $em, CryptoHelper $cryptoHelper)
    {
        $this->em = $em;
        $this->cryptoHelper = $cryptoHelper;
    }

    public function generateRandom(int $bytes): string
    {
        $base64EncodedRandom = Utils::base64Encode($this->cryptoHelper->generateRandom($bytes));

        $random = new Random();
        $random->setBase64EncodedRandom($base64EncodedRandom);

        $expiresOn = (new DateTime())->modify(sprintf('+%d seconds', getenv('EXPIRATION_TIME_OF_RANDOM_IN_SECONDS')));
        $random->setExpiresOn($expiresOn);

        $this->em->persist($random);
        $this->em->flush();

        return $base64EncodedRandom;
    }
}