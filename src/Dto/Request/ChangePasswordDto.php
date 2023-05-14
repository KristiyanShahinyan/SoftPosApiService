<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordDto implements DtoInterface
{
    /**
     * @var string|null
     * @Assert\NotBlank(message="The оld password must not be blank.")
     */
    protected $oldPassword;

    /**
     * @var string|null
     * @Assert\NotBlank(message="The new password must not be blank.")
     * @Assert\Length(min="6", minMessage="PASSWORD_TOO_SHORT")
     * @Assert\Expression(
     *     expression="this.getOldPassword() != this.getNewPassword()",
     *     message="PASSWORDS_MUST_BE_DIFFERENT"
     * )
     */
    protected $newPassword;

    /**
     * @return string|null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    /**
     * @param string|null $oldPassword
     */
    public function setOldPassword(?string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return string|null
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * @param string|null $newPassword
     */
    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }
}
