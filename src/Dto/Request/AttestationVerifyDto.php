<?php
/**
 * Created by PhpStorm.
 * User: venci
 * Date: 28.11.18
 * Time: 18:33
 */

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AttestationVerifyDto
{
    /**
     * @var array
     *
     * @Assert\Count(min=3)
     * @Assert\NotBlank()
     */
    private $certificates;

    /**
     * @return array
     */
    public function getCertificates(): array
    {
        return $this->certificates;
    }

    /**
     * @param array $certificates
     */
    public function setCertificates(array $certificates): void
    {
        $this->certificates = $certificates;
    }

}
