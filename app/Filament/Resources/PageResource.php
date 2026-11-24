<?php
namespace App\Filament\Resources;

use App\Models\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\PageResource\Pages;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationLabel = 'Pages';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Page content')->schema([
        TextInput::make('path')->required()->unique(ignoreRecord: true)->helperText('Keep exact legacy path, including the leading slash.'),
        TextInput::make('title')->required(),TextInput::make('eyebrow'),
        Textarea::make('intro')->rows(2),MarkdownEditor::make('body')->columnSpanFull(),
        TextInput::make('hero_image')->columnSpanFull(),
    ])->columns(2),
    Section::make('Dynamic sections & page presentation')->description('Reusable sections for editorial pages; the active public page automatically renders approved content blocks.')->schema([
        Select::make('layout_variant')->options(['editorial'=>'Editorial','split'=>'Split introduction','gallery'=>'Gallery','feature'=>'Visual feature','journal'=>'Journal'])->default('editorial'),
        TextInput::make('hero_video')->label('Optional background MP4 path')->helperText('Upload and optimize video before using; reduced-motion visitors see the static poster.'),
        TextInput::make('hero_video_poster')->label('Video poster image URL'),
        TextInput::make('youtube_url')->label('Optional YouTube video URL')->url()->columnSpanFull()->helperText('Loads only after the visitor selects Play; not an autoplay background.'),
        TextInput::make('cta_text')->label('Primary call to action'),TextInput::make('cta_url')->label('CTA destination'),
        Repeater::make('sections')->label('Page sections')->schema([
            Select::make('type')->options(['editorial'=>'Editorial story','gallery'=>'Image gallery','statement'=>'Pull quote / Statement','services'=>'Service CTA','video'=>'Video / Media','youtube'=>'YouTube Video Overlay','cta'=>'Inquiry CTA'])->required(),
            TextInput::make('eyebrow'),TextInput::make('heading')->required(),Textarea::make('body')->rows(3),
            TextInput::make('image_url')->label('Image URL')->url(),
            TextInput::make('youtube_url')->label('Optional YouTube URL')->url(),
            TextInput::make('cta_text'),TextInput::make('cta_url'),
        ])->reorderable()->columns(2)->columnSpanFull(),
    ])->columns(2),
    Section::make('SEO and launch readiness')->schema([
        TextInput::make('meta_title'),Textarea::make('meta_description'),
        Toggle::make('is_published'),Toggle::make('content_verified'),
    ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable(),TextColumn::make('path')->searchable(),IconColumn::make('content_verified')->boolean(),IconColumn::make('is_published')->boolean()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
