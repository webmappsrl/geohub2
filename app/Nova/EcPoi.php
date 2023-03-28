<?php

namespace App\Nova;

use Laravel\Nova\Panel;
use Davidpiesse\Map\Map;
use Eminiarts\Tabs\Tabs;
use Wm\MapPoint\MapPoint;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Davidpiesse\NovaToggle\Toggle;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphToMany;
use Chaseconey\ExternalImage\ExternalImage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Khalin\Nova4SearchableBelongsToFilter\NovaSearchableBelongsToFilter;
use Laravel\Nova\Fields\Markdown;

class EcPoi extends Resource
{


    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\EcPoi>
     */
    public static $model = \App\Models\EcPoi::class;

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
        if ($request->user()->isAdmin()) {
            return $query;
        }
        return $query->where('user_id', $request->user()->id);
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
            ID::make()->sortable(),
            NovaTabTranslatable::make([
                Text::make(__('name'), 'name'),
                Markdown::make(__('description'), 'description')->hideFromIndex(),
                Text::make(__('excerpt'), 'excerpt')->hideFromIndex(),
            ])->setTitle('Name'),
            BelongsTo::make('User'),
            DateTime::make(__('Created At'), 'created_at')->sortable(),
            DateTime::make(__('Updated At'), 'updated_at')->sortable(),
            MorphToMany::make('Taxonomy Themes', 'taxonomyThemes')->searchable(),

        ];
    }


    public function fieldsForDetail()
    {
        return [
            (new Tabs(
                "EC Poi Details: {$this->name} ({$this->id})",
                [
                    'Main' => $this->mainTab(),
                    'Media' => $this->mediaTab(),
                    'Style' => $this->styleTab(),
                    'Info' => $this->infoTab(),
                    'Accessibility' => $this->accessibilityTab(),
                    'Reachability' => $this->reachabilityTab(),
                ]
            ))->withToolbar(),
            MapPoint::make(__('Map'), 'geometry')->withMeta([
                'center' => ["51", "4"],
                'attribution' => '<a href="https://webmapp.it/">Webmapp</a> contributors',
                'tiles' => 'https://api.webmapp.it/tiles/{z}/{x}/{y}.png',
                'minZoom' => 7,
                'maxZoom' => 16,
                'defaultZoom' => 12
            ])->onlyOnDetail(),
            MorphToMany::make('Taxonomy Themes', 'taxonomyThemes'),
        ];
    }

    public function fieldsForUpdate()
    {
        return [
            (new Tabs(
                "New EC Poi",
                [
                    'Main' => [
                        NovaTabTranslatable::make([
                            Text::make(__('Name'), 'name'),
                            Textarea::make(__('Excerpt'), 'excerpt'),
                            //! not working
                            //!NovaTinyMCE::make(__('Description'), 'description'),
                            Textarea::make(__('Description'), 'description'),
                        ]),
                        BelongsTo::make('User'),
                        MapPoint::make(__('Map'), 'geometry')->withMeta([
                            'center' => ["51", "4"],
                            'attribution' => '<a href="https://webmapp.it/">Webmapp</a> contributors',
                            'tiles' => 'https://api.webmapp.it/tiles/{z}/{x}/{y}.png',
                            'minZoom' => 7,
                            'maxZoom' => 16,
                            'defaultZoom' => 12
                        ])->hideFromIndex(),
                    ],
                    'Media' => [
                        File::make(__('Audio'), 'audio')->store(function (Request $request, $model) {
                            return $model->uploadAudio($request->file());
                        })->acceptedTypes('audio/*')->onlyOnForms(),
                    ],
                    'Style' => $this->styleTab(),
                    'Info' => $this->infoTab(),
                    'Accessibility' => $this->accessibilityTab(),
                    'Reachability' => $this->reachabilityTab(),
                ],


                //!to fix
                //! new Panel('Map / Geographical info', [
                // !    WmEmbedmapsField::make(__('Map'), 'geometry', function () use ($geojson) {
                // !        return [
                // !            'feature' => $geojson,
                // !        ];
                //!    }),
                //! ]),
            )), MorphToMany::make('Taxonomy Themes', 'taxonomyThemes')

        ];
    }

    public function fieldsForCreate(NovaRequest $request)
    {
        return $this->fieldsForUpdate($request);
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
        if ($request->user()->isAdmin()) return [
            (new NovaSearchableBelongsToFilter('User'))
                ->fieldAttribute('user')
                ->filterBy('user_id'),
        ];

        return [];
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
        return [];
    }

    //* Defining tabs
    private function mainTab()
    {
        return [
            Text::make('Geohub ID', function () {
                return $this->id;
            }),
            Text::make('User', function () {
                return $this->user->name;
            }),
            DateTime::make('Created At')->onlyOnDetail(),
            DateTime::make('Updated At')->onlyOnDetail(),
            NovaTabTranslatable::make([
                Text::make(__('Name'), 'name'),
                Textarea::make(__('Excerpt'), 'excerpt'),
                //! NovaTinyMCE not working, used Textarea
                Textarea::make(__('Description'), 'description'),
            ])->onlyOnDetail(),
        ];
    }
    private function mediaTab()
    {
        return [
            Text::make(__('Audio'), 'audio'),
            Text::make('Related Url', function () {
                $out = '';
                if (is_array($this->related_url) && count($this->related_url) > 0) {
                    foreach ($this->related_url as $label => $url) {
                        $out .= "<a href='{$url}' target='_blank'>{$label}</a></br>";
                    }
                } else {
                    $out = "No related Url";
                }
                return $out;
            })->asHtml(),
            //!to fix
            //! ExternalImage::make(__('Feature Image'), function () {
            //!     $url = isset($this->model()->featureImage) ? $this->model()->featureImage->url : '';
            //!    if ('' !== $url && substr($url, 0, 4) !== 'http') {
            //!         $url = Storage::disk('public')->url($url);
            //!    }
            //!     return $url;
            //!})->withMeta(['width' => 400])->onlyOnDetail(),
        ];
    }

    private function styleTab()
    {
        return [
            Color::make(__('Color'), 'color')
                ->default('#de1b0d')
                ->hideFromIndex(),
            Text::make(__('Z index'), 'zindex'),
            Toggle::make(__('No Interaction'), 'noInteraction'),
            Toggle::make(__('No Details'), 'noDetails')

        ];
    }

    private function infoTab()
    {
        return [
            Text::make('Contact Phone'),
            Text::make('Contact Email'),
            Text::make('Adress / complete', 'addr_complete'),
            Text::make('Adress / street', 'addr_street'),
            Text::make('Adress / housenumber', 'addr_housenumber'),
            Text::make('Adress / postcode', 'addr_postcode'),
            Text::make('Adress / locality', 'addr_locality'),
            Text::make('Opening Hours'),
            Number::Make('Elevation', 'ele'),
            Text::make('Capacity'),
            Text::make('Stars'),
            Text::make('Code'),
        ];
    }

    private function accessibilityTab()
    {
        return  [
            DateTime::make(__('Last verification date'), 'accessibility_validity_date'),
            File::make(__('Accessibility PDF'), 'accessibility_pdf')->disk('public')
                ->acceptedTypes('.pdf'),
            Toggle::make(__('Access Mobility Check'), 'access_mobility_check'),
            Select::make(__('Access Mobility Level'), 'access_mobility_level')->options([
                'accessible_independently' => 'Accessible independently',
                'accessible_with_assistance' => 'Accessible with assistance',
                'accessible_with_a_guide' => 'Accessible with a guide',
            ]),
            Textarea::make(__('Access Mobility Description'), 'access_mobility_description'),

            Toggle::make(__('Access Hearing Check'), 'access_hearing_check'),
            Select::make(__('Access Hearing Level'), 'access_hearing_level')->options([
                'accessible_independently' => 'Accessible independently',
                'accessible_with_assistance' => 'Accessible with assistance',
                'accessible_with_a_guide' => 'Accessible with a guide',
            ]),
            Textarea::make(__('Access Hearing Description'), 'access_hearing_description'),
            Toggle::make(__('Access Vision Check'), 'access_vision_check'),
            Select::make(__('Access Vision Level'), 'access_vision_level')->options([
                'accessible_independently' => 'Accessible independently',
                'accessible_with_assistance' => 'Accessible with assistance',
                'accessible_with_a_guide' => 'Accessible with a guide',
            ]),
            Textarea::make(__('Access Vision Description'), 'access_vision_description'),

            Toggle::make(__('Access Cognitive Check'), 'access_cognitive_check'),
            Select::make(__('Access Cognitive Level'), 'access_cognitive_level')->options([
                'accessible_independently' => 'Accessible independently',
                'accessible_with_assistance' => 'Accessible with assistance',
                'accessible_with_a_guide' => 'Accessible with a guide',
            ]),
            Textarea::make(__('Access Cognitive Description'), 'access_cognitive_description'),

            Toggle::make(__('Access Food Check'), 'access_food_check'),
            Textarea::make(__('Access Food Description'), 'access_food_description'),
        ];
    }

    private function reachabilityTab()
    {
        return [
            Toggle::make(__('Reachability by Bike'), 'reachability_by_bike_check'),
            Textarea::make(__('Reachability by Bike Description'), 'reachability_by_bike_description'),
            Toggle::make(__('Reachability on Foot'), 'reachability_on_foot_check'),
            Textarea::make(__('Reachability on Foot Description'), 'reachability_on_foot_description'),
            Toggle::make(__('Reachability by Car'), 'reachability_by_car_check'),
            Textarea::make(__('Reachability by Car Description'), 'reachability_by_car_description'),
            Toggle::make(__('Reachability by Public Transportation'), 'reachability_by_public_transportation_check'),
            Textarea::make(__('Reachability by Public Transportation Description'), 'reachability_by_public_transportation_description'),
        ];
    }
}
