<?php

namespace App\Filament\App\Resources;

use Filament\Support\Icons\Heroicon;
use Tapp\FilamentAuditing\Filament\Resources\Audits\AuditResource as OriginalAuditResource;
use UnitEnum;
use BackedEnum;

class AuditResource extends OriginalAuditResource
{
    public static function getNavigationGroup(): ?string
    {
        return __('filament/navigation.groups.admin');
    }

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon  = Heroicon::OutlinedDocumentText;

    protected static string|BackedEnum|null $activeNavigationIcon  = Heroicon::DocumentText;
}
