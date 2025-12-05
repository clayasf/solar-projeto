<?php

namespace App\Domain\Enums;

enum TipoInstalacao: string
{
    case FIBROCIMENTO_MADEIRA = 'Fibrocimento (Madeira)';
    case FIBROCIMENTO_METALICO = 'Fibrocimento (Metálico)';
    case CERAMICO = 'Cerâmico';
    case METALICO = 'Metálico';
    case LAJE = 'Laje';
    case SOLO = 'Solo';

    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromValor(string $valor): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $valor) {
                return $case;
            }
        }
        return null;
    }
}