<?php

namespace App\Dto;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="decrypt_encrypt_request_response_interface")
 */
interface SecurityInterface
{


    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param $data
     * @return mixed
     */
    public function setData($data);
}
