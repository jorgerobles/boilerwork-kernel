#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Boilerwork\Foundation\Entities;

use Boilerwork\Events\AbstractEvent;
use Boilerwork\Support\ValueObjects\Identity;

/**
 * Receive Events from persistence, check events belong to their owner aggregate and convert them to an array of DomainEvents
 */
final class AggregateHistory
{
    /**
     * @var AbstractEvent[]
     */
    private array $history = [];

    public function __construct(
        private Identity $aggregateId,
        private readonly array $events
    ) {
        foreach ($events as $event) {
            $event = $event['type']::unserialize($event);

            if ($event->aggregateId() !== $aggregateId->toPrimitive()) {
                throw new \Exception('Aggregate history is corrupted');
            }

            $this->history[] = $event;
        }
    }

    public function aggregateId(): Identity
    {
        return $this->aggregateId;
    }

    /**
     * @return AbstractEvent[]
     */
    public function aggregateHistory(): array
    {
        return $this->history;
    }
}
