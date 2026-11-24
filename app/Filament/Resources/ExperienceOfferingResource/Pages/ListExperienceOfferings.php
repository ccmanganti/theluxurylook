<?php
namespace App\Filament\Resources\ExperienceOfferingResource\Pages;
use App\Filament\Resources\ExperienceOfferingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListExperienceOfferings extends ListRecords {protected static string $resource=ExperienceOfferingResource::class;protected function getHeaderActions():array{return [CreateAction::make()];}}
