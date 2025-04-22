<?php

namespace App\Service;

use App\Entity\AuditLog;
use Doctrine\ORM\EntityManagerInterface;

class AuditService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function logAudit(
        string $entity,
        int $entityId,
        string $action,
        array $changeSet = [],
        string $username = 'anonymous'
    ): void {
        // Create AuditLog
        $audit = new AuditLog();
        $audit->setEntity($entity);
        $audit->setEntityId($entityId);
        $audit->setAction($action);
        $audit->setChangeSet($changeSet);
        $audit->setUsername($username);
        $audit->setLoggedAt(new \DateTimeImmutable());

        // Persist the log
        $this->em->persist($audit);
        $this->em->flush(); // Ensure it is flushed to the DB
    }

    public function getChangeSet(object $old, object $new, array $fields): array
    {
        $changeSet = [];
        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($old, $getter) && method_exists($new, $getter)) {
                $oldValue = $old->$getter();
                $newValue = $new->$getter();
                if ($oldValue !== $newValue) {
                    $changeSet[$field] = [$oldValue, $newValue];
                }
            }
        }

        return $changeSet;
    }
}
