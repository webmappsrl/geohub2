<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use App\Nova\User;
use App\Nova\EcPoi;
use App\Nova\EcTrack;
use App\Nova\TaxonomyTheme;
use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Menu\MenuSection;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $this->getFooter();
        $this->getCustomMenu();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function getFooter()
    {
        Nova::footer(function () {
            return Blade::render('nova/footer');
        });
    }

    private function getCustomMenu()
    {
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class),
                MenuSection::make('Content', [
                    MenuItem::resource(EcTrack::class),
                    MenuItem::resource(EcPoi::class),
                    MenuItem::resource(TaxonomyTheme::class)
                ])->icon('document-text')->collapsable(),
                MenuSection::make('Admin', [
                    MenuItem::resource(User::class),
                ])->icon('user')->collapsable(),
            ];
        });
    }
}