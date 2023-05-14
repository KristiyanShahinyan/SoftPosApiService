<?php
/**
 * Created by PhpStorm.
 * User: venci
 * Date: 28.11.18
 * Time: 18:33
 */

namespace App\Dto\Request;

class AttestationDetailsDto
{
    /**
     * @var array|null
     */
    protected $imeis;

    /**
     * @var array|null
     */
    protected $details;

    /**
     * @return array|null
     */
    public function getImeis(): ?array
    {
        return $this->imeis;
    }

    /**
     * @param array|null $imeis
     */
    public function setImeis(?array $imeis): void
    {
        $this->imeis = $imeis;
    }

    /**
     * @return array|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @param array|null $details
     */
    public function setDetails(?array $details): void
    {
        $this->details = $details;
    }

}
