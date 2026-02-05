<?php

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum RequestStatus: string implements HasLabel, HasIcon, HasDescription
{
    case Sent = 'sent';
    case Processing = 'processing';
    case Ordered = 'ordered';
    case Received = 'received';
    case Cancelled = 'cancelled';

    public function getLabel(): string | Htmlable | null
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Sent => 'gray',
            self::Processing => 'warning',
            self::Ordered => 'success',
            self::Received => 'primary',
            self::Cancelled => 'danger',
        };
    }

    public function getIcon(): string | \BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Sent => Heroicon::Pencil,
            self::Processing => Heroicon::Eye,
            self::Ordered => Heroicon::Check,
            self::Received => Heroicon::Check,
            self::Cancelled => Heroicon::XMark,
        };
    }

    public function getDescription(): string | Htmlable | null
    {
        return match ($this) {
            self::Sent => 'This request has been sent.',
            self::Processing => 'This request is being processed.',
            self::Ordered => 'The products of this request have been ordered.',
            self::Received => 'The products of this request have been received.',
            self::Cancelled => 'This request has been cancelled.',
        };
    }
}
