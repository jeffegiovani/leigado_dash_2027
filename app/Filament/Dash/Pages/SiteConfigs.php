<?php

namespace App\Filament\Dash\Pages;

use App\Enums\AttendantSegmentEnum;
use App\Enums\SiteConfigKeyEnum;
use App\Filament\Forms\Components\WebpImageUpload;
use App\Models\SiteConfig;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class SiteConfigs extends Page
{
    use HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $title = 'Opções do Site';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected string $view = 'filament.dash.pages.site-configs';

    /**
     * Estado do formulário da página.
     *
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this
            ->form
            ->fill([
                SiteConfigKeyEnum::WhatsappAttendants->value => SiteConfig::normalizeAttendantSegments(
                    SiteConfig::valueFor(SiteConfigKeyEnum::WhatsappAttendants, [])
                ),
                SiteConfigKeyEnum::PrivacyPolicy->value => SiteConfig::valueFor(SiteConfigKeyEnum::PrivacyPolicy),
                SiteConfigKeyEnum::TermsOfUse->value => SiteConfig::valueFor(SiteConfigKeyEnum::TermsOfUse),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Schemas\Components\Tabs::make('site-configs')
                    ->columnSpanFull()
                    ->persistTabInQueryString()
                    ->tabs([
                        Schemas\Components\Tabs\Tab::make(SiteConfigKeyEnum::WhatsappAttendants->getLabel())
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                self::whatsappAttendantsField(),
                            ]),

                        Schemas\Components\Tabs\Tab::make(SiteConfigKeyEnum::PrivacyPolicy->getLabel())
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                self::legalContentField(SiteConfigKeyEnum::PrivacyPolicy),
                            ]),

                        Schemas\Components\Tabs\Tab::make(SiteConfigKeyEnum::TermsOfUse->getLabel())
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                self::legalContentField(SiteConfigKeyEnum::TermsOfUse),
                            ]),
                    ]),
            ]);
    }

    /**
     * Lista de atendentes exibidos na WhatsApp Bubble do site.
     */
    protected static function whatsappAttendantsField(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make(SiteConfigKeyEnum::WhatsappAttendants->value)
            ->hiddenLabel()
            ->addActionLabel('Adicionar atendente')
            ->reorderable()
            ->orderColumn()
            ->collapsible()
            ->cloneable()
            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
            ->defaultItems(0)
            ->columns([
                'md' => 2,
            ])
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(60)
                    ->label('Nome'),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->hint(new HtmlString('<small>Somente números, com DDI. Ex: 5546999119511</small>'))
                    ->label('WhatsApp'),

                Forms\Components\TextInput::make('phone_formatted')
                    ->maxLength(30)
                    ->hint(new HtmlString('<small>Como o número aparece no site. Ex: +55 46 9 9911 9511</small>'))
                    ->label('Fone Formatado'),

                Forms\Components\TextInput::make('location')
                    ->maxLength(60)
                    ->hint(new HtmlString('<small>Opcional. Ex: Carambeí - Campos Gerais, PR</small>'))
                    ->label('Região de Atendimento'),

                Forms\Components\Textarea::make('whatsapp_message')
                    ->required()
                    ->rows(3)
                    ->maxLength(300)
                    ->columnSpanFull()
                    ->hint(new HtmlString('<small>Mensagem já preenchida na conversa do WhatsApp</small>'))
                    ->label('Mensagem Inicial'),

                WebpImageUpload::make('avatar')
                    ->required()
                    ->directory('site-configs/attendants')
                    ->imageEditor()
                    ->imageEditorMode(2)
                    ->imageAspectRatio('1:1')
                    ->automaticallyCropImagesToAspectRatio()
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyResizeImagesToWidth('96')
                    ->automaticallyResizeImagesToHeight('96')
                    ->resizeOnServer()
                    ->optimize('webp')
                    ->label('Foto'),

                Schemas\Components\Group::make()
                    ->columns(1)
                    ->schema([
                        Forms\Components\CheckboxList::make('segments')
                            ->options(AttendantSegmentEnum::class)
                            ->descriptions(collect(AttendantSegmentEnum::cases())
                                ->mapWithKeys(fn (AttendantSegmentEnum $segment): array => [
                                    $segment->value => $segment->getDescription(),
                                ])
                                ->all())
                            ->required()
                            ->default(AttendantSegmentEnum::defaults())
                            ->label('Aparece em'),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Ativo'),
                    ]),
            ]);
    }

    /**
     * Editor de conteúdo das páginas legais do site.
     */
    protected static function legalContentField(SiteConfigKeyEnum $key): Forms\Components\RichEditor
    {
        return Forms\Components\RichEditor::make($key->value)
            ->fileAttachmentsDisk('public')
            ->fileAttachmentsVisibility('public')
            ->fileAttachmentsDirectory('site-configs/legal')
            ->columnSpanFull()
            ->hiddenLabel()
            ->label($key->getLabel());
    }

    /**
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->action('save')
                ->keyBindings(['mod+s'])
                ->label('Salvar Alterações'),
        ];
    }

    public function save(): void
    {
        $data = $this
            ->form
            ->getState();

        $previousAvatars = self::attendantAvatars(
            SiteConfig::valueFor(SiteConfigKeyEnum::WhatsappAttendants, [])
        );

        foreach (SiteConfigKeyEnum::cases() as $key) {
            SiteConfig::store($key, $data[$key->value] ?? null);
        }

        $this->deleteOrphanAttendantAvatars(
            $previousAvatars,
            self::attendantAvatars($data[SiteConfigKeyEnum::WhatsappAttendants->value] ?? [])
        );

        Notification::make()
            ->success()
            ->title('Opções do site atualizadas')
            ->send();
    }

    /**
     * Avatares referenciados por uma lista de atendentes.
     *
     * @param  mixed  $attendants
     * @return array<int, string>
     */
    protected static function attendantAvatars($attendants): array
    {
        if (! is_array($attendants)) {
            return [];
        }

        return collect($attendants)
            ->pluck('avatar')
            // O estado do FileUpload pode vir como array indexado por uuid antes de ser gravado.
            ->flatMap(fn ($avatar): array => is_array($avatar) ? array_values($avatar) : [$avatar])
            ->filter(fn ($avatar): bool => is_string($avatar) && filled($avatar))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Remove do disco os avatares que deixaram de ser referenciados.
     *
     * @param  array<int, string>  $previousAvatars
     * @param  array<int, string>  $currentAvatars
     */
    protected function deleteOrphanAttendantAvatars(array $previousAvatars, array $currentAvatars): void
    {
        $orphans = array_values(array_diff($previousAvatars, $currentAvatars));

        if ($orphans === []) {
            return;
        }

        Storage::disk('public')->delete($orphans);
    }
}
