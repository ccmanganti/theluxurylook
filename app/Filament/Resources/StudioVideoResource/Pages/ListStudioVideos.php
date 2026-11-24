<?php
namespace App\Filament\Resources\StudioVideoResource\Pages;
use App\Filament\Resources\StudioVideoResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListStudioVideos extends ListRecords {protected static string $resource=StudioVideoResource::class;protected function getHeaderActions():array{return [CreateAction::make()];}}
