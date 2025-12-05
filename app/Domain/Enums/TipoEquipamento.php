<?php

namespace App\Domain\Enums;

enum TipoEquipamento: string
{
    case MODULO = 'Módulo';
    case INVERSOR = 'Inversor';
    case MICROINVERSOR = 'Microinversor';
    case ESTRUTURA = 'Estrutura';
    case CABO_VERMELHO = 'Cabo vermelho';
    case CABO_PRETO = 'Cabo preto';
    case STRING_BOX = 'String Box';
    case CABO_TRONCO = 'Cabo Tronco';
    case ENDCAP = 'EndCap';

    public static function valores(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function categoria(): string
    {
        return match($this) {
            self::MODULO, self::INVERSOR, self::MICROINVERSOR => 'Geração',
            self::ESTRUTURA, self::STRING_BOX, self::ENDCAP => 'Estrutura/Montagem',
            self::CABO_VERMELHO, self::CABO_PRETO, self::CABO_TRONCO => 'Cabeamento',
        };
    }
}