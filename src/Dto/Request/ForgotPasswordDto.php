<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ForgotPasswordDto implements DtoInterface
{
    public const GROUP_CREATE_TOKEN = 'create_token';
    public const GROUP_SET_NEW_PASSWORD = 'set_new_password';

    /**
     * @var string|null
     * @Assert\NotBlank(message="The e-mail is missing.", normalizer="trim", groups={"create_token"})
     */
    protected $email;

    /**
     * @var string|null
     * @Assert\NotBlank(message="The token is missing.", normalizer="trim", groups={"set_new_password"})
     */
    protected $token;

    /**
     * @var string|null
     * @Assert\NotBlank(message="The new password is missing.", normalizer="trim", groups={"set_new_password"})
     */
    protected $newPassword;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }
}

