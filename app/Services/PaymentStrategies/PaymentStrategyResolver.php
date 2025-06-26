<?php

namespace App\Services\PaymentStrategies;


class PaymentStrategyResolver
{
    public static function resolve(string $method): PaymentStrategyInterface
    {
        return match ($method) {
            'tax_and_service' => new TaxAndServiceStrategy(),
            'service_only'    => new ServiceOnlyStrategy(),
            default => throw new \InvalidArgumentException("Unsupported payment method: $method"),
        };
    }
}