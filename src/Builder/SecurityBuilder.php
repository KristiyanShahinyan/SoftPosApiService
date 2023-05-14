<?php

namespace App\Builder;

use App\Dto\Request\SecurityDto;

/**
 * Class EncryptResponseBuilder
 * @package App\Builder
 */
class SecurityBuilder
{
    /**
     * @param string|null $data
     * @param bool $compress
     * @return SecurityDto
     */
    public function build(string $data = null, bool $compress = false): SecurityDto
    {
        $dto = new SecurityDto();
        $dto->setData($data);
        $dto->setCompress($compress);

        return $dto;
    }

}
