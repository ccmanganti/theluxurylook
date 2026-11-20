<?php
namespace App\Filament\Resources;

use App\Models\PortfolioCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\PortfolioCategoryResource\Pages;

class PortfolioCategoryResource extends Resource
{
    protected static ?string $model = PortfolioCategory::class;
    protected static ?string $navigationLabel = 'Portfolio Categories';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Portfolio category')->schema([
        TextInput::make('title')->required(),TextInput::make('slug')->required()->unique(ignoreRecord: true),
        TextInput::make('tagline'),Textarea::make('description')->rows(3),TextInput::make('hero_image')->label('Photography URL'),
        TextInput::make('sort_order')->numeric()->default(0),Toggle::make('is_published'),Toggle::make('content_verified')->helperText('Confirm category imagery, copy, titles and every linked project.'),
    ])->columns(2),
    Section::make('SEO')->schema([TextInput::make('meta_title'),Textarea::make('meta_description')]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable(),TextColumn::make('slug'),IconColumn::make('is_published')->boolean(),TextColumn::make('projects_count')->counts('projects')->label('Projects')])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolioCategories::route('/'),
            'create' => Pages\CreatePortfolioCategory::route('/create'),
            'edit' => Pages\EditPortfolioCategory::route('/{record}/edit'),
        ];
    }
}
