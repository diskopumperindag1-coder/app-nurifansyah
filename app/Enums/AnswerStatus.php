<?php

namespace App\Enums;

enum AnswerStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Diterbitkan (Published)',
            self::Rejected => 'Ditolak (Rejected)',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'success',
            self::Rejected => 'danger',
        };
    }
}
