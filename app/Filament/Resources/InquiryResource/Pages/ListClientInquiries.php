<?php
namespace App\Filament\Resources\InquiryResource\Pages;

use App\Filament\Resources\InquiryResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListClientInquiries extends ListRecords
{
    protected static string $resource = InquiryResource::class;
    protected function getHeaderActions(): array {return [];}
}
