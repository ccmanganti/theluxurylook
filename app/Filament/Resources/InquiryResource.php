<?php
namespace App\Filament\Resources;

use App\Models\Inquiry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Select,MarkdownEditor,Repeater,FileUpload,DateTimePicker};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\InquiryResource\Pages;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;
    protected static ?string $navigationLabel = 'Client Inquiries';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

    Section::make('Received inquiry')->schema([
        TextInput::make('name')->disabled(),TextInput::make('email')->disabled(),
        TextInput::make('phone')->disabled(),TextInput::make('service')->disabled(),
        Textarea::make('message')->disabled()->rows(8)->columnSpanFull(),
    ])->columns(2),
    Section::make('Follow-up')->schema([Select::make('status')->options(['new'=>'New','contacted'=>'Contacted','qualified'=>'Qualified','closed'=>'Closed'])->required()]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([TextColumn::make('name')->searchable(),TextColumn::make('email')->searchable(),TextColumn::make('service'),TextColumn::make('status')->badge(),TextColumn::make('created_at')->dateTime()->sortable()])->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientInquiries::route('/'),

            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}
