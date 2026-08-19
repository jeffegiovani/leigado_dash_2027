<?php

namespace App\Filament\Dash\Resources;

use App\Enums\ResourceVisibilityEnum;
use App\Enums\SegmentsEnum;
use App\Filament\Dash\Resources\CouponResource\Pages;
use App\Filament\Forms\Components\WebpImageUpload;
use App\Models\Coupon;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Cupons de Desconto'; // Para Menus e para o Spotlight

    // protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Cupom de Desconto';

    protected static ?string $pluralModelLabel = 'Cupons de Desconto';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 8,
                    ])
                    ->schema([
                        Schemas\Components\Group::make()
                            ->columns(1)
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ])
                            ->schema([
                                Schemas\Components\Section::make()
                                    ->columnSpan([
                                        'default' => 1,
                                    ])
                                    ->heading(null)
                                    ->hiddenLabel()
                                    ->schema([
                                        Forms\Components\CheckboxList::make('segments')
                                            ->required()
                                            ->options(SegmentsEnum::class)
                                            // ->columnSpanFull()
                                            ->bulkToggleable()
                                            // ->columns(3)
                                            ->label('Segmentos Contemplados'),
                                    ]),

                                Forms\Components\Select::make('author_id')
                                    ->required()
                                    ->relationship(
                                        name: 'author',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: function (Builder $query): Builder {
                                            if (auth()->user()->id != 1) {
                                                $query->where('id', '!=', 1);
                                            }

                                            return $query;
                                        }
                                    )
                                    ->default(auth()->id())
                                    ->selectablePlaceholder(false)
                                    ->label('Responsável pela Campanha'),

                                WebpImageUpload::make('avatar')
                                    // ->required(fn($operation) => $operation === 'create')
                                    ->required()
                                    // ->columnSpanFull()
                                    ->directory('coupons/avatars')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->imageResizeMode('cover')
                                    // ->imageCropAspectRatio('1:1')
                                    // ->imageEditorViewportWidth(1280)
                                    // ->imageEditorViewportHeight(720)
                                    ->imageResizeTargetWidth('160')
                                    ->imageResizeTargetHeight('160')
                                    ->imageEditorAspectRatios([
                                        null,
                                    ])
                                    ->optimize('webp')
                                    ->label('Avatar da Campanha'),

                                WebpImageUpload::make('cover')
                                    // ->required(fn($operation) => $operation === 'create')
                                    // ->columnSpanFull()
                                    ->directory('coupons/covers')
                                    ->imageEditor()
                                    ->imageEditorMode(2)
                                    // ->imageEditorEmptyFillColor('#000000')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('8:3')
                                    ->imageEditorViewportWidth(900)
                                    ->imageEditorViewportHeight(338)
                                    ->imageResizeTargetWidth('900')
                                    ->imageResizeTargetHeight('338')
                                    ->optimize('webp')
                                    ->label('Capa da Campanha'),
                            ]),

                        Schemas\Components\Group::make()
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->columnSpan([
                                'md' => 2,
                                'lg' => 6,
                            ])
                            ->schema([
                                Forms\Components\ToggleButtons::make('visibility')
                                    ->options(ResourceVisibilityEnum::class)
                                    // ->inlineLabel()
                                    ->hiddenLabel()
                                    ->required()
                                    ->default(ResourceVisibilityEnum::Public)
                                    ->inline()
                                    // ->hint(fn() => view('forms.resource-visibility-hint-helper'))
                                    ->columnSpanFull()
                                    ->label('Visibilidade'),

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(120)
                                    ->columnSpanFull()
                                    ->label('Título da Campanha'),

                                Forms\Components\TextInput::make('slug')
                                    // ->unique()
                                    ->columnSpanFull()
                                    ->maxLength(125)
                                    ->default(null)
                                    ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                                    // ->hintIcon('heroicon-s-question-mark-circle')
                                    ->label('Slug de Acesso'),

                                Forms\Components\TextInput::make('partner')
                                    ->required()
                                    ->maxLength(80)
                                    ->label('Parceiro de Negócios'),

                                Forms\Components\TextInput::make('offer_headline')
                                    ->required()
                                    ->maxLength(20)
                                    ->label('Headline de 20 chars'),

                                Forms\Components\Textarea::make('cta')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(191)
                                    ->default(null)
                                    ->label('Chamada'),

                                Forms\Components\MarkdownEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                        'codeBlock',
                                        'heading',
                                        // 'link',
                                        'table',
                                    ])
                                    ->label('Detalhamento'),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->searchable()
                    ->label('Responsável'),

                Tables\Columns\TextColumn::make('visibility')
                    ->sortable()
                    ->searchable()
                    ->label('Visibilidade'),

                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->label('Avatar'),

                Tables\Columns\ImageColumn::make('cover')
                    ->label('Capa'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Título'),

                Tables\Columns\TextColumn::make('offer_headline')
                    ->searchable()
                    ->label('Headline'),

                // Tables\Columns\TextColumn::make('cta')
                //     ->searchable()
                //     ->label(''),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Edição'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'view' => Pages\ViewCoupon::route('/{record}'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
