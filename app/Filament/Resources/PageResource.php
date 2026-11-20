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
