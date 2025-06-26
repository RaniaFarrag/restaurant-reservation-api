<?php

namespace App\Services\PaymentStrategies;


class ServiceOnlyStrategy implements PaymentStrategyInterface
{

    public function calculate(float $amount): float
    {
        $service = 0.15 * $amount;
        return $amount + $service;
    }

}