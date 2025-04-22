<?php

namespace App\EventListener;

use App\Service\AuditService;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class AuthenticationListener
{
    public function __construct(private AuditService $auditService) {}

    #[AsEventListener(event: InteractiveLoginEvent::class)]
    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (is_object($user) && method_exists($user, 'getId')) {
            $this->auditService->logAudit(
                'user',
                $user->getId(),
                'login',
                [],
                $user->getUserIdentifier()
            );
        }
    }
}
