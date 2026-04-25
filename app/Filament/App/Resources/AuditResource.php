<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Tapp\FilamentAuditing\Filament\Resources\Audits\AuditResource as OriginalAuditResource;

class AuditResource extends OriginalAuditResource
{
    public static function canAccess(): bool
    {
        return Auth::user()?->hasRole('super_admin') ?? false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.admin');
    }

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::DocumentText;
}
