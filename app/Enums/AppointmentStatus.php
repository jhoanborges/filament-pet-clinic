<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum AppointmentStatus: string implements HasLabel, HasColor
{
    use IsKanbanStatus;

    case Created = 'created';
    case Attended = 'attended';
    case Canceled = 'canceled';

    public function getLabel(): ?string
    {
        return $this->status;
    }
    

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Created => 'warning',
            self::Attended => 'success',
            self::Canceled => 'danger',
        };
    }

    
    
}
