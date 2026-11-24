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
        TextInput::make('title')->label('Original approved project name')->required()->maxLength(250)
            ->helperText('Keep the original public/SEO name until the real name is approved. It is used at launch.'),
        TextInput::make('preview_title')->label('Working project name (preview only)')->maxLength(250)
            ->helperText('Displays only while the site is in review mode and the project is unverified. Safe to replace at any time.'),
        TextInput::make('slug')->required()->unique(ignoreRecord: true)->helperText('Do not change existing legacy slugs without adding an approved 301 redirect.'),
        Select::make('portfolio_category_id')->relationship('category','title')->searchable()->preload(),
        TextInput::make('location')->maxLength(250),
        Textarea::make('summary')->rows(3),
        MarkdownEditor::make('story')->label('Approved project narrative')->columnSpanFull(),
    ])->columns(2),
    Section::make('Case study / Verified proof')->description('Client brief, scope, before photographs, approved outcomes and permissioned quotes.')->schema([
        Textarea::make('project_brief')->label('Client brief / design challenge')->rows(3)->columnSpanFull(),
        MarkdownEditor::make('design_approach')->label('Design approach and decisions')->columnSpanFull(),
        Textarea::make('scope_of_work')->label('Rooms, services and project scope')->rows(3)->columnSpanFull(),
        Repeater::make('before_gallery')->label('Before photographs')->schema([
            TextInput::make('url')->label('Image URL')->url()->required(),
            TextInput::make('alt')->label('Alt text'),
        ])->reorderable()->columns(2)->columnSpanFull(),
        Textarea::make('result_statement')->label('Verified results only')->rows(3)->columnSpanFull(),
        Repeater::make('metrics')->label('Result figures / cite sources')->schema([
            TextInput::make('label')->required()->maxLength(100),TextInput::make('value')->required()->maxLength(90),
            TextInput::make('source')->label('Source / evidence')->maxLength(255),
        ])->reorderable()->columns(3)->columnSpanFull(),
        Textarea::make('client_quote')->label('Approved quote')->rows(3)->columnSpanFull(),
        TextInput::make('quote_attribution')->label('Author / company')->maxLength(255),
        Toggle::make('results_verified')->label('Verified for publication')->default(false),
    ])->columns(2),
    Section::make('Approved photography / Replace image placeholders')->schema([
        TextInput::make('cover_image')->label('Legacy CDN image URL')->columnSpanFull(),
        FileUpload::make('cover_upload')->disk('public')->directory('projects/covers')->image()->imageEditor(),
        Repeater::make('gallery')->schema([TextInput::make('url')->required(),TextInput::make('alt')])->columns(2)->columnSpanFull(),
        FileUpload::make('gallery_uploads')->disk('public')->directory('projects/gallery')->multiple()->image()->reorderable()->columnSpanFull()->helperText('Approved uploaded photos hide all temporary gallery photos in staging.'),
    ]),
    Section::make('Preview-only gallery / Optional replacements')->schema([
        TextInput::make('preview_cover_image')->label('Temporary hero URL')->url()->columnSpanFull(),
        Repeater::make('preview_gallery')->label('Temporary gallery')->schema([
            TextInput::make('url')->label('Image URL')->required()->url(), TextInput::make('alt'),
        ])->reorderable()->columns(2)->columnSpanFull(),
    ])->collapsed(),
    Section::make('SEO and launch readiness')->schema([
        TextInput::make('meta_title')->maxLength(255),Textarea::make('meta_description')->rows(2),TextInput::make('seo_locality')->label('Verified locality (optional)')->helperText('Only specify a city genuinely relevant to this content; the studio is based in Scottsdale.'),
        TextInput::make('seo_share_image')->label('Social preview image URL')->url()->helperText('Use approved imagery only.'),
        Toggle::make('featured')->label('Feature on homepage'),
        Toggle::make('builder_featured')->label('Candidate for Builders & Agents')->helperText('Select 4-6 compelling examples for the builder page; verify the story and permission before launch.'),
        Select::make('case_study_type')->label('Case-study category')->options([
            'Residential design'=>'Residential design',
            'Interior design / renovation'=>'Renovation / design',
            'Luxury home staging'=>'Luxury home staging',
            'Occupied staging'=>'Occupied seller refresh',
            'Luxury property'=>'Luxury property',
            'Property presentation'=>'Property presentation',
            'Spec home'=>'New construction spec home',
            'Model home'=>'Builder model / showcase',
            'Turnkey second home'=>'Turnkey second home'
        ]),
        Toggle::make('is_published'),Toggle::make('content_verified')->helperText('Approve only after copy, gallery, OG tags, and media ownership have been reviewed.'),
        TextInput::make('sort_order')->numeric()->default(0),
    ])->columns(2),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('preview_title')->label('Working title')->searchable(),TextColumn::make('title')->label('Original title / SEO')->searchable()->sortable(),TextColumn::make('category.title')->label('Category'),IconColumn::make('featured')->boolean(),IconColumn::make('builder_featured')->boolean()->label('Builder feature'),IconColumn::make('content_verified')->boolean()->label('Verified'),TextColumn::make('updated_at')->dateTime()->sortable()])->defaultSort('updated_at', 'desc');
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
