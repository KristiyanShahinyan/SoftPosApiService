<?php

namespace App\Controller\V1;

use App\Service\MessageDispatcherService;
use Phos\Controller\AbstractApiController;
use Phos\Dto\Log\Audit\AuditLogInterface;
use Phos\Service\MobileAuditLog;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

class AuditController extends AbstractApiController
{
    protected MobileAuditLog $auditLogService;

    private MessageDispatcherService $messageDispatcherService;

    private ParameterBagInterface $parameterBag;

    public function __construct(MobileAuditLog $auditLogService, MessageDispatcherService $messageDispatcherService, ParameterBagInterface $parameterBag)
    {
        $this->auditLogService = $auditLogService;
        $this->messageDispatcherService = $messageDispatcherService;
        $this->parameterBag = $parameterBag;
    }

    public function log(Request $request)
    {
        if ($this->parameterBag->get('ENABLE_AUDIT_LOGGER'))
            $this->auditLogService->log($request);

        $this->messageDispatcherService->logMessage($request->server->getHeaders(), $request->getContent() ?? []);

        return $this->success();
    }

    public function logBulk(Request $request)
    {
        if ($this->parameterBag->get('ENABLE_AUDIT_LOGGER'))
            $this->auditLogService->logBulk($request);
        $this->messageDispatcherService->logMessage($request->server->getHeaders(), $request->getContent() ?? [], true);

        return $this->success();
    }
}
