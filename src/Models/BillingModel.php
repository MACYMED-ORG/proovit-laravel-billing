<?php

declare(strict_types=1);

namespace Proovit\Billing\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read int|null $id
 * @property-read string|null $uuid_identifier
 */
abstract class BillingModel extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('uuid_identifier')) {
                $model->setAttribute('uuid_identifier', (string) Str::uuid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid_identifier';
    }

    /**
     * @return Factory<static>|null
     */
    protected static function newFactory(): ?Factory
    {
        $factoryClass = 'Proovit\\Billing\\Database\\Factories\\'.class_basename(static::class).'Factory';

        if (class_exists($factoryClass)) {
            /** @var Factory<static> $factory */
            $factory = $factoryClass::new();

            return $factory;
        }

        return null;
    }
}
