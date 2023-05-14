<?php

namespace App\Helper;

use Aws\Kms\KmsClient;

/**
 * Class CryptoHelper
 * @package App\Helper
 */
class CryptoHelper
{
    /**
     * @var KmsClient
     */
    protected KmsClient $client;

    /**
     * CryptoHelper constructor.
     * @param KmsClient $client
     */
    public function __construct(KmsClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $size
     * @return mixed
     */
    public function generateRandom($size)
    {
        $result = $this->client->generateRandom([
            'NumberOfBytes' => $size,
        ]);

        return $result['Plaintext'];
    }

}
