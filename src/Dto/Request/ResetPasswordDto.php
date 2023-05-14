<?php
/**
 * Created by PhpStorm.
 * User: dian
 * Date: 4.09.18
 * Time: 0:27
 */

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ResetPasswordDto
 * @package App\Dto\Request
 */
class ResetPasswordDto implements DtoInterface
{
    /**
     * @Assert\NotBlank()
     */
    protected $token;

    /**
     * @Assert\NotBlank()
     */
    protected $newPassword;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

}
