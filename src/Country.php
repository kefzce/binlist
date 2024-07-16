<?php

declare(strict_types=1);

namespace App;

class Country
{
    public const string AT = 'AT';
    public const string BE = 'BE';
    public const string BG = 'BG';
    public const string CY = 'CY';
    public const string CZ = 'CZ';
    public const string DE = 'DE';
    public const string DK = 'DK';
    public const string EE = 'EE';
    public const string ES = 'ES';
    public const string FI = 'FI';
    public const string FR = 'FR';
    public const string GR = 'GR';
    public const string HR = 'HR';
    public const string HU = 'HU';
    public const string IE = 'IE';
    public const string IT = 'IT';
    public const string LT = 'LT';
    public const string LU = 'LU';
    public const string LV = 'LV';
    public const string MT = 'MT';
    public const string NL = 'NL';
    public const string PL = 'PL'; // Corrected from 'PO' to 'PL'
    public const string PT = 'PT';
    public const string RO = 'RO';
    public const string SE = 'SE';
    public const string SI = 'SI';
    public const string SK = 'SK';

    public const array EU_COUNTRIES = [
        self::AT,
        self::BE,
        self::BG,
        self::CY,
        self::CZ,
        self::DE,
        self::DK,
        self::EE,
        self::ES,
        self::FI,
        self::FR,
        self::GR,
        self::HR,
        self::HU,
        self::IE,
        self::IT,
        self::LT,
        self::LU,
        self::LV,
        self::MT,
        self::NL,
        self::PL,
        self::PT,
        self::RO,
        self::SE,
        self::SI,
        self::SK,
    ];

    public const float EU_BASED_TRANSACTION_COST = 0.01;
    public const float NON_EU_BASED_TRANSACTION_COST = 0.02;

    public string $country;
    public bool $isEuBased;

    public function __construct(string $country)
    {
        $this->country = $country;
        $this->isEuBased = self::isEu($country);
    }

    public static function isEu(string $countryCode): bool
    {
        return \in_array($countryCode, self::EU_COUNTRIES, true);
    }
}
