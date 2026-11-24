<?php
namespace App\Filament\Resources;
use App\Models\FounderProfile;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{TextInput,Textarea,Toggle,Repeater,FileUpload};
use Filament\Tables\{Table,Columns\TextColumn,Columns\IconColumn};
use App\Filament\Resources\FounderProfileResource\Pages;

class FounderProfileResource extends Resource {
    protected static ?string $model=FounderProfile::class;
    protected static ?string $navigationLabel='Meet Kristine';
    protected static ?int $navigationSort=2;
    public static function form(Schema $schema):Schema {
        return $schema->components([
          Section::make('Founder identity')->description('Founder profile used on the homepage and About page. Draft language requires Kristine’s approval before publishing.')->schema([
            TextInput::make('name')->required()->maxLength(255), TextInput::make('role')->required()->maxLength(255),TextInput::make('eyebrow'),
            TextInput::make('headline')->required()->columnSpanFull(),Textarea::make('lead')->rows(4)->columnSpanFull(),
            Textarea::make('short_bio')->rows(4)->columnSpanFull(),
            TextInput::make('founding_year')->label('Founded (verified)'),TextInput::make('experience_label')->label('Experience claim (review before launch)'),
          ])->columns(2),
          Section::make('Portraits & real photography')->description('Upload approved photos to replace the currently referenced original company/personal website images. Uploaded files take precedence over source URLs.')->schema([
            FileUpload::make('portrait_upload')->disk('public')->directory('founder/portraits')->image()->imageEditor(),
            TextInput::make('portrait_url')->label('Original company portrait URL')->url(),
            FileUpload::make('headshot_upload')->disk('public')->directory('founder/headshots')->image()->imageEditor(),
            TextInput::make('headshot_url')->label('Published Kristine headshot URL')->url(),
            FileUpload::make('secondary_photo_upload')->disk('public')->directory('founder/story')->image()->imageEditor(),
            TextInput::make('secondary_photo_url')->label('Second company photo URL')->url(),
          ])->columns(2),
          Section::make('Full biography / About Me')->schema([
            Textarea::make('career_bio')->label('Career & founding story')->rows(7)->columnSpanFull(),
            Textarea::make('approach_bio')->label('Design philosophy and collaboration')->rows(7)->columnSpanFull(),
            Textarea::make('beyond_bio')->label('Education, buying trips and beyond the studio')->rows(6)->columnSpanFull(),
            Repeater::make('principles')->label('Approach pillars')->schema([TextInput::make('title')->required(),Textarea::make('description')->required()->rows(3)])->reorderable()->columns(2)->columnSpanFull(),
          ]),
          Section::make('Founder on film')->description('The Sedona interview features Kristine; the player loads only after visitors click.')->schema([
            TextInput::make('interview_url')->label('Approved YouTube or Vimeo URL')->url()->columnSpanFull(),
            TextInput::make('interview_poster_url')->label('Film poster image URL')->url()->columnSpanFull(),
          ]),
          Section::make('Approval & provenance')->schema([
            Textarea::make('source_notes')->label('Editorial source and image permissions')->rows(3)->columnSpanFull(),
            Toggle::make('is_published')->label('Show on review site'),
            Toggle::make('content_verified')->label('Kristine approved for production')->helperText('The founder profile stays hidden in launch mode until bio and photo rights are approved.'),
          ])->columns(2),
        ]);
    }
    public static function table(Table $table):Table {
        return $table->columns([TextColumn::make('name')->searchable(),TextColumn::make('role'),IconColumn::make('is_published')->boolean(),IconColumn::make('content_verified')->label('Approved')->boolean(),TextColumn::make('updated_at')->dateTime()->sortable()]);
    }
    public static function getPages():array{return ['index'=>Pages\ListFounderProfiles::route('/'),'create'=>Pages\CreateFounderProfile::route('/create'),'edit'=>Pages\EditFounderProfile::route('/{record}/edit')];}
}
