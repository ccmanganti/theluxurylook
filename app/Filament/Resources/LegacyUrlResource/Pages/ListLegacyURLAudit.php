<?php
namespace App\Filament\Resources\LegacyUrlResource\Pages;

use App\Filament\Resources\LegacyUrlResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListLegacyURLAudit extends ListRecords
{
    protected static string $resource = LegacyUrlResource::class;
    protected function getHeaderActions(): array {return [CreateAction::make()];}
}
