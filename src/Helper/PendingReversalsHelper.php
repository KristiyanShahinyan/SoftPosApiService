<?php

namespace App\Helper;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class PendingReversalsHelper
{
    private array $pendingReversals;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerBagInterface $params)
    {
        $this->pendingReversals = $params->get('pending_reversals');
    }

    public function shouldClearReversals(string $acquiringInstitutionIdentificationCode): bool
    {
        $pendingReversals = $this->pendingReversals[$acquiringInstitutionIdentificationCode] ?? $this->pendingReversals['default'];

        return $pendingReversals['should_clear_reversals'];
    }
}