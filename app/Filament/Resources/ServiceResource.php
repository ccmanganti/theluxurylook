<?php
namespace App\Filament\Resources;

use App\Models\Service;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\ServiceResource\Pages;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationLabel = 'Services';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Service offering')->schema([
        TextInput::make('title')->required(),TextInput::make('slug')->required()->unique(ignoreRecord: true),
        Textarea::make('summary')->rows(3),MarkdownEditor::make('body')->columnSpanFull(),
        TextInput::make('cover_image')->label('Legacy CDN image URL')->columnSpanFull(),
        FileUpload::make('cover_upload')->disk('public')->directory('services')->image(),
        TextInput::make('sort_order')->numeric()->default(0),Toggle::make('is_published'),Toggle::make('content_verified')->helperText('Approve original service details and imagery before go-live.'),
    ])->columns(2),
    Section::make('Temporary service image')->description('Visible only in the development preview, not after launch.')->schema([TextInput::make('preview_cover_image')->url()->label('Temporary cover URL')])->collapsed(),
    Section::make('SEO')->schema([TextInput::make('meta_title'),Textarea::make('meta_description')]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('title')->searchable(),TextColumn::make('slug'),IconColumn::make('is_published')->boolean(),TextColumn::make('sort_order')->sortable()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
