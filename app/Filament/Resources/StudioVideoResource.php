<?php
namespace App\Filament\Resources;

use App\Models\StudioVideo;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Select,Toggle};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\StudioVideoResource\Pages;

class StudioVideoResource extends Resource {
    protected static ?string $model=StudioVideo::class;
    protected static ?string $navigationLabel='Studio Videos';
    protected static ?int $navigationSort=6;
    public static function form(Schema $schema):Schema {
        return $schema->components([
            Section::make('YouTube film / Click-to-play')->description('YouTube embeds are only loaded when a visitor clicks play; video is never zoomed, parallaxed or automatically started.')->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('youtube_url')->required()->url()->maxLength(2048)->columnSpanFull(),
                TextInput::make('poster_url')->label('Thumbnail / poster URL')->url()->columnSpanFull(),
                Select::make('placement')->options(['home'=>'Home / Studio film','services'=>'Services','buying-trips'=>'Buying Trips','education'=>'Education','about'=>'About'])->required(),
                Textarea::make('caption')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Section::make('Review and display')->schema([
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_published')->label('Show in designated location'),
                Toggle::make('content_verified')->label('Approved original company footage')->helperText('Verify ownership, embedding availability and context before using on the production website.'),
            ])->columns(3)
        ]);
    }
    public static function table(Table $table):Table {
        return $table->columns([
            TextColumn::make('title')->searchable(),TextColumn::make('placement')->sortable(),
            IconColumn::make('is_published')->boolean(),IconColumn::make('content_verified')->boolean(),
            TextColumn::make('updated_at')->dateTime()->sortable()
        ]);
    }
    public static function getPages():array {return [
        'index'=>Pages\ListStudioVideos::route('/'),
        'create'=>Pages\CreateStudioVideo::route('/create'),
        'edit'=>Pages\EditStudioVideo::route('/{record}/edit'),
    ];}
}
