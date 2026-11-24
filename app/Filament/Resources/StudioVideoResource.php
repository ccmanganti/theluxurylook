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
            Section::make('Film / YouTube or Vimeo')->description('Paste a YouTube link, a Vimeo watch link or a Vimeo player URL. For unlisted Vimeo videos keep the h= hash. Video providers load only when someone presses Play.')->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('video_url')->label('Video URL (YouTube or Vimeo)')->required()->url()->maxLength(2048)->columnSpanFull()
                    ->rules([fn() => function (string $attribute, $value, $fail): void {
                        if (!StudioVideo::sourceInfo(is_string($value)?$value:null)) $fail('Please enter a supported HTTPS YouTube or Vimeo URL.');
                    }]),
                TextInput::make('poster_url')->label('Thumbnail / poster URL')->url()->columnSpanFull(),
                Select::make('placement')->options(['home'=>'Featured studio film','gallery'=>'Film gallery','services'=>'Services','buying-trips'=>'Buying Trips','education'=>'Education','about'=>'About','archive'=>'Archive (not displayed)'])->required(),
                Textarea::make('caption')->rows(2)->columnSpanFull(),
            ])->columns(2),
            Section::make('Review and display')->schema([
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_published')->label('Show in the preview'),
                Toggle::make('content_verified')->label('Approved for public launch')
                    ->helperText('Confirm permission, the correct title and provider embedding settings before production launch.'),
            ])->columns(3),
        ]);
    }
    public static function table(Table $table):Table {
        return $table->columns([
            TextColumn::make('title')->searchable(),
            TextColumn::make('provider')->label('Provider'),
            TextColumn::make('placement')->sortable(),
            IconColumn::make('is_published')->boolean(),IconColumn::make('content_verified')->boolean(),
            TextColumn::make('updated_at')->dateTime()->sortable()
        ])->defaultSort('sort_order');
    }
    public static function getPages():array {return [
        'index'=>Pages\ListStudioVideos::route('/'),
        'create'=>Pages\CreateStudioVideo::route('/create'),
        'edit'=>Pages\EditStudioVideo::route('/{record}/edit'),
    ];}
}
