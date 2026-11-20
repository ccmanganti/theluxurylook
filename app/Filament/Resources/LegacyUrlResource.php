<?php
namespace App\Filament\Resources;

use App\Models\LegacyUrl;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\LegacyUrlResource\Pages;

class LegacyUrlResource extends Resource
{
    protected static ?string $model = LegacyUrl::class;
    protected static ?string $navigationLabel = 'Legacy URL Audit';
    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Original URL')->schema([
        TextInput::make('path')->required()->unique(ignoreRecord: true),
        TextInput::make('source_url')->required(),TextInput::make('content_type'),
        Toggle::make('included_in_original_sitemap'),
    ]),
    Section::make('Migration decision')->schema([
        Select::make('status')->options(['needs_content'=>'Needs content','ready'=>'Verified / Ready','redirect'=>'301 Redirect','retired'=>'Retired / Approved'])->required(),
        TextInput::make('redirect_to')->helperText('Only absolute local paths starting with /; use only when redirect is approved.'),
        Textarea::make('migration_notes')->rows(5),
    ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('path')->searchable()->copyable(),TextColumn::make('content_type'),TextColumn::make('status')->badge(),IconColumn::make('included_in_original_sitemap')->boolean()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLegacyURLAudit::route('/'),
            'create' => Pages\CreateLegacyUrl::route('/create'),
            'edit' => Pages\EditLegacyUrl::route('/{record}/edit'),
        ];
    }
}
