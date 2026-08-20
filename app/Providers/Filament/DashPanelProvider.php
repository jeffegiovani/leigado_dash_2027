<?php

namespace App\Providers\Filament;

use App\Filament\Dash\Pages\Dashboard;
use App\Filament\Dash\Resources\Blogs\Widgets\BlogArticlesCountWidget;
use App\Filament\Dash\Resources\Coupons\Widgets\CouponsCountWidget;
use App\Filament\Dash\Resources\Faqs\Widgets\FaqCountWidget;
use App\Filament\Dash\Resources\Jobs\Widgets\JobsCountWidget;
use App\Filament\Dash\Resources\SiteContacts\Widgets\SiteContactsCountWidget;
use App\Filament\Dash\Resources\SuccessCases\Widgets\SuccessCasesCountWidget;
use App\Filament\Dash\Widgets\AccountWidget;
use App\Models\User;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class DashPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentColor::register([
            'gray' => [
                50 => '#eceff4',  // nord6 - snow storm
                100 => '#e5e9f0', // nord5 - snow storm
                200 => '#d8dee9', // nord4 - snow storm
                300 => '#a7b1c5',
                400 => '#8c9ab3',
                500 => '#71829b',
                600 => '#4c566a', // nord3 - polar night
                700 => '#434c5e', // nord2 - polar night
                800 => '#3b4252', // nord1 - polar night
                900 => '#2e3440', // nord0 - polar night
                950 => '#232831',
            ],
        ]);

        Page::formActionsAlignment(Alignment::Right);

        Action::configureUsing(function (Action $action): void {
            $action->modalFooterActionsAlignment(Alignment::Right);
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->selectCurrentPageOnly()
                ->persistSortInSession()
                ->persistFiltersInSession()
                ->deferLoading()
                ->deferFilters(false)
                ->recordUrl(null);
        });

        Column::configureUsing(function (Column $column): void {
            $column->toggleable();
        });

        return $panel
            ->default()
            ->id('dash')
            ->path('')
            ->login()
            ->passwordReset()
            ->spa()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::hex('#5e81ac'),   // nord8
                'secondary' => Color::hex('#88c0d0'), // nord10
                'danger' => Color::hex('#bf616a'),    // nord11
                'info' => Color::hex('#81a1c1'),      // nord9
                'success' => Color::hex('#a3be8c'),   // nord14
                'warning' => Color::hex('#ebcb8b'),   // nord13
            ])
            ->navigationGroups([
                NavigationGroup::make()->label('Publicações do Blog'),
                NavigationGroup::make()->label('Perguntas Frequentes'),
                NavigationGroup::make()->label('Configurações'),
            ])
            ->maxContentWidth(Width::ScreenTwoExtraLarge)
            ->sidebarWidth('270px')
            ->unsavedChangesAlerts(fn (): bool => app()->isProduction())
            ->brandName('Gestão Leigado')
            ->viteTheme('resources/css/filament/dash/theme.css')
            ->favicon(asset('img/favicon-96x96.png'))
            ->discoverResources(in: app_path('Filament/Dash/Resources'), for: 'App\Filament\Dash\Resources')
            ->discoverPages(in: app_path('Filament/Dash/Pages'), for: 'App\Filament\Dash\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                SiteContactsCountWidget::class,
                BlogArticlesCountWidget::class,
                CouponsCountWidget::class,
                FaqCountWidget::class,
                JobsCountWidget::class,
                SuccessCasesCountWidget::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->readOnlyRelationManagersOnResourceViewPagesByDefault()
            ->plugins([
                FilamentShieldPlugin::make(),

                AuthUIEnhancerPlugin::make(),

                ...$this->developerLoginsPlugins(),

                FilamentEditProfilePlugin::make()
                    ->slug('profile')
                    ->setTitle('Perfil')
                    ->setNavigationLabel('Perfil')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    ->shouldRegisterNavigation(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens(false)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(),

                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(fn (): bool => auth()->id() === 1),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ]);
    }

    /**
     * O plugin de login de desenvolvedor é uma dependência de desenvolvimento,
     * portanto não está disponível em produção (`composer install --no-dev`).
     *
     * @return array<int, Plugin>
     */
    protected function developerLoginsPlugins(): array
    {
        if (! app()->environment('local')) {
            return [];
        }

        if (! class_exists(FilamentDeveloperLoginsPlugin::class)) {
            return [];
        }

        return [
            FilamentDeveloperLoginsPlugin::make()
                ->users(fn (): array => User::pluck('email', 'name')->toArray()),
        ];
    }
}
