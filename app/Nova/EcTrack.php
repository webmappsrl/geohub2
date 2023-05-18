<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use App\Nova\Actions\editThemes;
use App\Nova\Filters\ThemeFilter;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Markdown;
use App\Nova\Metrics\TracksMetric;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphToMany;
use Datomatic\NovaMarkdownTui\MarkdownTui;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wm\MapMultiLinestring\MapMultiLinestring;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Khalin\Nova4SearchableBelongsToFilter\NovaSearchableBelongsToFilter;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\Textarea;

class EcTrack extends Resource
{
    use HasTabs;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\EcTrack>
     */
    public static $model = \App\Models\EcTrack::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'description'
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        //if user is admin can see all
        if ($request->user()->isAdmin()) {
            return $query;
        }
        //if user is editor can only see his own tracks
        if ($request->user()->isEditor()) {
            return $query->where('user_id', $request->user()->id);
        }
    }



    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Tabs::make('Track', [
                Tab::make('Main Info', [
                    ID::make()->sortable(),

                    NovaTabTranslatable::make([
                        Text::make(__('name'), 'name'),
                        Textarea::make(__('excerpt'), 'excerpt')
                            ->hideFromIndex()
                            ->alwaysShow(),
                        MarkdownTui::make(__('description'), 'description')
                            ->hideFromIndex(),
                    ])->setTitle(__('Name')),
                    $request->user()->isAdmin() ? BelongsTo::make('User') : BelongsTo::make('User')->onlyOnIndex(),
                    DateTime::make(__('Created At'), 'created_at')->sortable(),
                    DateTime::make(__('Updated At'), 'updated_at')->sortable(),
                    Text::make('Geohub ID', 'geohub_id')->onlyOnDetail(),
                    MapMultiLinestring::make('geometry')->withMeta([
                        'center' => ["43", "10"],
                        'attribution' => '<a href="https://webmapp.it/">Webmapp</a> contributors',
                        'tiles' => 'https://api.webmapp.it/tiles/{z}/{x}/{y}.png',
                        'defaultZoom' => 10,
                        'graphhopper_api' => 'https://graphhopper.webmapp.it/route'
                    ])->hideFromIndex(),
                    MorphToMany::make('Taxonomy Themes', 'taxonomyThemes')->searchable(),
                ]),
                Tab::make('Style', [
                    Color::make('Color', 'color')->hideFromIndex(),
                ])
            ])->withToolbar(),



        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {

        //if user is admin can filter by user
        if ($request->user()->isAdmin()) return [
            (new NovaSearchableBelongsToFilter('User'))
                ->fieldAttribute('user')
                ->filterBy('user_id'),
        ];
        //if user is editor can filter by themes related to his tracks
        if ($request->user()->isEditor()) return [
            (new ThemeFilter)
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        // if user is editor can edit themes
        if ($request->user()->isEditor()) {
            return [
                (new editThemes)
                    ->confirmText('Update Taxonomy Themes')
                    ->confirmButtonText('Yes, edit the themes')
                    ->cancelButtonText('No, cancel')
            ];
        }

        return [];
    }
}
