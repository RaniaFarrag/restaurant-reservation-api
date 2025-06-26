<?php

namespace App\Services\PaymentStrategies;


class TaxAndServiceStrategy implements PaymentStrategyInterface
{

    public function calculate(float $amount): float
    {
        $tax = 0.14 * $amount;
        $service = 0.20 * $amount;
        return $amount + $tax + $service;
    }

}
