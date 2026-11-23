<?php
namespace App\Filament\Resources;

use App\Models\Project;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\ProjectResource\Pages;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationLabel = 'Projects';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Portfolio project')->schema([
        TextInput::make('title')->required()->maxLength(250),
        TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Do not change existing legacy slugs without adding an approved 301 redirect.'),
        Select::make('portfolio_category_id')->relationship('category','title')->searchable()->preload(),
        TextInput::make('location')->maxLength(250),
        Textarea::make('summary')->rows(3),
        MarkdownEditor::make('story')->columnSpanFull(),
    ])->columns(2),
    Section::make('Final project photography — uploads take priority')->schema([
        TextInput::make('cover_image')->label('Legacy CDN image URL')->columnSpanFull(),
        FileUpload::make('cover_upload')->disk('public')->directory('projects/covers')->image()->imageEditor(),
        Repeater::make('gallery')->schema([TextInput::make('url')->required(),TextInput::make('alt')])->columns(2)->columnSpanFull(),
        FileUpload::make('gallery_uploads')->disk('public')->directory('projects/gallery')->multiple()->image()->reorderable()->columnSpanFull()
            ->helperText('Once even one approved image is uploaded, sample gallery images are hidden on staging. Upload the full project set before marking verified.'),
    ]),
    Section::make('Temporary preview photography — staging only')->description('These photos show layout only and will NEVER appear when SITE_LAUNCH_READY=true. Replace via the upload fields above.')->schema([
        TextInput::make('preview_cover_image')->label('Temporary cover URL')->url()->columnSpanFull(),
        Repeater::make('preview_gallery')->label('Temporary gallery slots')->schema([
            TextInput::make('url')->label('Preview image URL')->required()->url(),
            TextInput::make('alt')->label('Placeholder description')
        ])->columns(2)->reorderable()->columnSpanFull(),
    ])->collapsed(),
    Section::make('SEO and launch readiness')->schema([
        TextInput::make('meta_title')->maxLength(255),Textarea::make('meta_description')->rows(2),
        Toggle::make('featured'),Toggle::make('is_published'),Toggle::make('content_verified')->helperText('Approve only after copy, gallery, OG tags, and media ownership have been reviewed.'),
        TextInput::make('sort_order')->numeric()->default(0),
    ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable()->sortable(),TextColumn::make('category.title')->label('Category'),IconColumn::make('featured')->boolean(),IconColumn::make('content_verified')->boolean()->label('Verified'),TextColumn::make('updated_at')->dateTime()->sortable()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
