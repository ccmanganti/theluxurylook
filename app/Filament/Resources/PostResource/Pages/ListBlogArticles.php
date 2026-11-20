<?php
namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListBlogArticles extends ListRecords
{
    protected static string $resource = PostResource::class;
    protected function getHeaderActions(): array {return [CreateAction::make()];}
}
