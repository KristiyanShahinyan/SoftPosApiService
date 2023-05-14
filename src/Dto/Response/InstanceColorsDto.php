<?php

namespace App\Dto\Response;

/**
 * Class ColorsModel
 * @package App\Dto\Response
 */
class InstanceColorsDto
{
    /**
     * @var string|null
     */
    private $primary = '#418bed';

    /**
     * @var string|null
     */
    private $buttonEnabled = '#ffdd21';

    /**
     * @var string|null
     */
    private $buttonDisabled = '#FFF6C3';

    /**
     * @var string|null
     */
    private $buttonPressed = '#1361C8';

    /**
     * @var string|null
     */
    private $loadingIndicator = '#8FBBF4';

    /**
     * @return string|null
     */
    public function getPrimary(): ?string
    {
        return $this->primary;
    }

    /**
     * @param string|null $primary
     */
    public function setPrimary(?string $primary): void
    {
        $this->primary = $primary;
    }

    /**
     * @return string|null
     */
    public function getButtonEnabled(): ?string
    {
        return $this->buttonEnabled;
    }

    /**
     * @param string|null $buttonEnabled
     */
    public function setButtonEnabled(?string $buttonEnabled): void
    {
        $this->buttonEnabled = $buttonEnabled;
    }

    /**
     * @return string|null
     */
    public function getButtonDisabled(): ?string
    {
        return $this->buttonDisabled;
    }

    /**
     * @param string|null $buttonDisabled
     */
    public function setButtonDisabled(?string $buttonDisabled): void
    {
        $this->buttonDisabled = $buttonDisabled;
    }

    /**
     * @return string|null
     */
    public function getButtonPressed(): ?string
    {
        return $this->buttonPressed;
    }

    /**
     * @param string|null $buttonPressed
     */
    public function setButtonPressed(?string $buttonPressed): void
    {
        $this->buttonPressed = $buttonPressed;
    }

    /**
     * @return string|null
     */
    public function getLoadingIndicator(): ?string
    {
        return $this->loadingIndicator;
    }

    /**
     * @param string|null $loadingIndicator
     */
    public function setLoadingIndicator(?string $loadingIndicator): void
    {
        $this->loadingIndicator = $loadingIndicator;
    }

}
