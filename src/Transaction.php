<?php

declare(strict_types=1);

namespace App;

class Transaction
{
    public ?Country $countryOrigin;
    public ?float $exchangeRate;
    public ?float $transactionCost;

    public array $errors = [];

    public function __construct(
        public int $bin,
        public float $amount,
        public string $currency,
        ?Country $countryOrigin = null,
        ?float $exchangeRate = null,
        ?float $transactionCost = null
    ) {
        $this->countryOrigin = $countryOrigin;
        $this->exchangeRate = $exchangeRate;
        $this->transactionCost = $transactionCost;
    }

    public function withCountry(Country $country): self
    {
        return new self(
            $this->bin,
            $this->amount,
            $this->currency,
            $country
        );
    }

    public function withRate(float $rate = 0.0): self
    {
        return new self(
            $this->bin,
            $this->amount,
            $this->currency,
            $this->countryOrigin,
            $rate
        );
    }

    public function withFee(float $fee): self
    {
        return new self(
            $this->bin,
            $this->amount,
            $this->currency,
            $this->countryOrigin,
            $this->exchangeRate,
            $fee
        );
    }
}
