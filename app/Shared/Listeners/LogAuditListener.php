<?php

namespace App\Shared\Listeners;

use App\Shared\Services\AuditService;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionProperty;

class LogAuditListener
{
    public function __construct(private readonly AuditService $auditService) {}

    public function handle(object $event): void
    {
        $subject = $this->extractSubject($event);

        if ($subject instanceof Model && $subject->exists) {
            $this->auditService->log(
                event: class_basename($event),
                subject: $subject,
                description: $this->buildDescription($event, $subject),
            );
        }
    }

    private function extractSubject(object $event): mixed
    {
        $reflection = new ReflectionClass($event);

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $value = $property->getValue($event);
            if ($value instanceof Model) {
                return $value;
            }
        }

        return null;
    }

    private function buildDescription(object $event, Model $subject): string
    {
        $eventName = class_basename($event);
        $modelName = class_basename($subject);

        return "{$modelName} {$eventName} (ID: {$subject->id})";
    }
}
