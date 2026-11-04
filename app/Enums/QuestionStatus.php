<?php

namespace App\Enums;

enum QuestionStatus: string
{
    case Pending = 'pending';
    case Answered = 'answered';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Jawaban (Pending)',
            self::Answered => 'Sudah Dijawab (Answered)',
            self::Rejected => 'Ditolak (Rejected)',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Answered => 'success',
            self::Rejected => 'danger',
        };
    }
}
