<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum OrderStatus: int implements HasColor, HasDescription, HasIcon, HasLabel
{
    case Sent = 1;
    case Processing = 2;
    case Ordered = 3;
    case Received = 4;
    case Cancelled = 5;
    case Closed = 6;

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Sent => 'gray',
            self::Processing => 'warning',
            self::Ordered => 'success',
            self::Received => 'primary',
            self::Cancelled => 'danger',
            self::Closed => 'info',
        };
    }

    public function getIcon(): string|\BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Sent => Heroicon::Pencil,
            self::Processing => Heroicon::Eye,
            self::Ordered => Heroicon::Check,
            self::Received => Heroicon::Check,
            self::Cancelled => Heroicon::XMark,
            self::Closed => Heroicon::CheckCircle,
        };
    }

    public function getDescription(): string|Htmlable|null
    {
        return match ($this) {
            self::Sent => 'This request has been sent.',
            self::Processing => 'This request is being processed.',
            self::Ordered => 'The products of this request have been ordered.',
            self::Received => 'The products of this request have been received.',
            self::Cancelled => 'This request has been cancelled.',
            self::Closed => 'This request has been closed.',
        };
    }

    /**
     * @return array<OrderStatus>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Sent => [self::Processing, self::Cancelled],
            self::Processing => [self::Ordered, self::Cancelled],
            self::Ordered => [self::Received, self::Cancelled],
            self::Received => [self::Closed, self::Cancelled],
            self::Closed, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    public function isTerminal(): bool
    {
        return $this === self::Closed || $this === self::Cancelled;
    }
}
