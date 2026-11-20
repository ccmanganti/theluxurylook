<?php
namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationLabel = 'Blog Articles';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Article')->schema([
        TextInput::make('title')->required()->columnSpanFull(),TextInput::make('slug')->required()->unique(ignoreRecord: true)->columnSpanFull(),
        Textarea::make('excerpt')->rows(3)->columnSpanFull(),MarkdownEditor::make('body')->columnSpanFull(),
        DateTimePicker::make('published_at'),
    ]),
    Section::make('Photography')->schema([
        TextInput::make('cover_image'),FileUpload::make('cover_upload')->disk('public')->directory('blog')->image(),
    ]),
    Section::make('SEO and launch readiness')->schema([
        TextInput::make('meta_title'),Textarea::make('meta_description'),Toggle::make('is_published'),
        Toggle::make('content_verified'),
    ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable(),TextColumn::make('published_at')->dateTime()->sortable(),IconColumn::make('content_verified')->boolean(),IconColumn::make('is_published')->boolean()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogArticles::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
