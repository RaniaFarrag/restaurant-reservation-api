<?php

namespace App\Services\PaymentStrategies;

interface PaymentStrategyInterface
{
    public function calculate(float $amount): float;
}