<?php

declare(strict_types=1);

namespace Proovit\Billing\Enums\Concerns;

use Illuminate\Support\Str;

trait HasTranslatableLabel
{
    public function label(): string
    {
        $enumName = Str::snake(class_basename(static::class));

        return __("billing::enums.{$enumName}.{$this->value}");
    }
}
