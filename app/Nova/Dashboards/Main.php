<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\UsersCount;
use App\Nova\Metrics\TracksCount;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        if (Auth::user()->isAdmin()) {
            return [
                new UsersCount,
                new TracksCount,



            ];
        }
        return [
            new TracksCount,
        ];
    }
}
