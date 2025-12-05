<?php

namespace App\Domain\Enums;

enum UF: string
{
    case AC = 'AC';
    case AL = 'AL';
    case AP = 'AP';
    case AM = 'AM';
    case BA = 'BA';
    case CE = 'CE';
    case DF = 'DF';
    case ES = 'ES';
    case GO = 'GO';
    case MA = 'MA';
    case MT = 'MT';
    case MS = 'MS';
    case MG = 'MG';
    case PA = 'PA';
    case PB = 'PB';
    case PR = 'PR';
    case PE = 'PE';
    case PI = 'PI';
    case RJ = 'RJ';
    case RN = 'RN';
    case RS = 'RS';
    case RO = 'RO';
    case RR = 'RR';
    case SC = 'SC';
    case SP = 'SP';
    case SE = 'SE';
    case TO = 'TO';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $uf): bool
    {
        return in_array(strtoupper($uf), self::values(), true);
    }

    public function region(): string
    {
        return match($this) {
            self::AC, self::AM, self::AP, self::PA, self::RO, self::RR, self::TO => 'Norte',
            self::AL, self::BA, self::CE, self::MA, self::PB, self::PE, self::PI, self::RN, self::SE => 'Nordeste',
            self::DF, self::GO, self::MT, self::MS => 'Centro-Oeste',
            self::ES, self::MG, self::RJ, self::SP => 'Sudeste',
            self::PR, self::RS, self::SC => 'Sul',
        };
    }
}