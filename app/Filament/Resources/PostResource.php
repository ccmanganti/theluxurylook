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
        Select::make('category')->options([
            'Interior Design'=>'Interior Design','Home Staging'=>'Home Staging',
            'Real Estate'=>'Real Estate','Travel & Living'=>'Travel & Living',
            'Seasonal Living'=>'Seasonal Living'
        ])->searchable(),
    ]),
    Section::make('Photography')->schema([
        TextInput::make('cover_image'),FileUpload::make('cover_upload')->disk('public')->directory('blog')->image(),
    ]),
    Section::make('SEO and launch readiness')->schema([
        TextInput::make('meta_title')->maxLength(255),
        Textarea::make('meta_description')->rows(2),
        TextInput::make('seo_locality')->label('Verified article locality')->helperText('Only add a city if the article actually concerns that location.'),
        TextInput::make('seo_share_image')->label('Social sharing image URL')->url(),
        TextInput::make('source_url')->label('Original published article URL')->url()->columnSpanFull(),
        TextInput::make('source_attribution')->label('Guest contributor / attribution')->helperText('Preserve original third-party authorship.'),
        Toggle::make('is_published'),
        Toggle::make('content_verified')->label('Editorial copy and rights approved')->helperText('Imported text remains review-only until attribution, date and images have been checked.'),
    ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable(),TextColumn::make('category'),TextColumn::make('published_at')->dateTime()->sortable(),IconColumn::make('content_verified')->boolean(),IconColumn::make('is_published')->boolean()])->defaultSort('updated_at', 'desc');
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
