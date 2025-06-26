<?php

namespace App\Services\PaymentStrategies;

class PaymentCalculator
{
    protected PaymentStrategyInterface $strategy;

    public function setStrategy(PaymentStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function calculate(float $amount): float
    {
        return $this->strategy->calculate($amount);
    }
}