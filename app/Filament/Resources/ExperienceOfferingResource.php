<?php
namespace App\Filament\Resources;
use App\Models\ExperienceOffering;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Select,MarkdownEditor,Repeater,FileUpload,Toggle};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\ExperienceOfferingResource\Pages;
class ExperienceOfferingResource extends Resource {
    protected static ?string $model=ExperienceOffering::class;
    protected static ?string $navigationLabel='Buying Trips, Dallas & Education';
    protected static ?int $navigationSort=4;
    public static function form(Schema $schema):Schema {
        return $schema->components([
            Section::make('Offering and audience')->schema([
                Select::make('kind')->label('Section')->options(['buying-trip'=>'Buying Trips (other journeys)','dallas'=>'Dallas Market (one destination)','education'=>'Education & Workshops'])->required(),
                TextInput::make('title')->required()->maxLength(250),TextInput::make('slug')->required()->maxLength(110)
                    ->helperText('Use a stable URL slug; update redirects before changing an existing published path.'),
                TextInput::make('eyebrow')->maxLength(160),TextInput::make('audience')->maxLength(255),
                Textarea::make('summary')->rows(3)->columnSpanFull(),MarkdownEditor::make('body')->columnSpanFull(),
                TextInput::make('cta')->label('Inquiry call to action')->maxLength(160),
            ])->columns(2),
            Section::make('Visuals and motion')->schema([
                TextInput::make('cover_image')->url()->columnSpanFull()->label('Cover image URL'),
                FileUpload::make('cover_upload')->disk('public')->directory('offerings/covers')->image()->imageEditor(),
                FileUpload::make('hero_video')->disk('public')->directory('offerings/video')->acceptedFileTypes(['video/mp4','video/webm'])->maxSize(12000)
                    ->helperText('Muted, short background loops only. Compress before upload; playback is lazy and skips reduced motion / data saving.'),
                FileUpload::make('video_poster')->disk('public')->directory('offerings/posters')->image(),
                TextInput::make('youtube_url')->label('Optional YouTube or Vimeo film URL')->url()->columnSpanFull()->helperText('Click-to-play film overlay only. Accepts approved YouTube/Vimeo links, including an unlisted Vimeo h= hash.'),
                Repeater::make('gallery')->label('Editorial gallery')->schema([
                    TextInput::make('url')->label('Image URL')->url()->required(),TextInput::make('alt')->maxLength(250)
                ])->reorderable()->columns(2)->columnSpanFull(),
                Repeater::make('highlights')->label('What is included / discussion topics')->schema([
                    TextInput::make('title')->required(),Textarea::make('body')->rows(2)
                ])->reorderable()->columns(2)->columnSpanFull()
            ])->columns(2),
            Section::make('Publication & SEO')->schema([
                Toggle::make('featured'),Toggle::make('is_published')->default(true),
                Toggle::make('content_verified')->default(false)->helperText('Do not approve until program dates, scope, instructors, pricing and claims are confirmed.'),
                TextInput::make('sort_order')->numeric()->default(0),TextInput::make('meta_title')->maxLength(250),
                Textarea::make('meta_description')->rows(2),
            ])->columns(2),
        ]);
    }
    public static function table(Table $table):Table {
        return $table->columns([
            TextColumn::make('title')->searchable()->sortable(),TextColumn::make('kind')->badge(),
            TextColumn::make('slug')->searchable(),IconColumn::make('featured')->boolean(),
            IconColumn::make('content_verified')->boolean()->label('Approved'),
            TextColumn::make('updated_at')->dateTime()->sortable(),
        ])->defaultSort('kind');
    }
    public static function getPages():array {return [
        'index'=>Pages\ListExperienceOfferings::route('/'),
        'create'=>Pages\CreateExperienceOffering::route('/create'),
        'edit'=>Pages\EditExperienceOffering::route('/{record}/edit')
    ];}
}
