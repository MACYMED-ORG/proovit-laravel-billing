<?php

declare(strict_types=1);

namespace Proovit\Billing\ValueObjects;

final readonly class LegalMentionSet
{
    /**
     * @param  array<int, string>  $items
     */
    public function __construct(public array $items = []) {}

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return array<int, string>
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
