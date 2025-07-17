<?php

namespace App\Enums;

enum StoreType: string {
    case COMIDA = 'COMIDA';
    case ROUPA = 'ROUPA';

    public function label(): string {
        return match($this) {
            self::COMIDA => 'Comida',
            self::ROUPA  => 'Roupa'
        };
    }
}