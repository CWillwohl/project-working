<?php

namespace App\Enums;

enum PunchTypeEnum: int
{
    case IN  = 0;
    case OUT = 1;

    public static function getDescription(int $value): string
    {
        switch ($value) {
            case self::IN->value:
                return 'Entrada';
                // no break
            case self::OUT->value:
                return 'Saida';
            default:
                return 'Desconhecido';
        }
    }
}
