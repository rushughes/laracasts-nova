<?php

namespace App\Nova;

use App\Nova\Actions\PublishPost;
use App\Nova\Filters\PostCategories;
use App\Nova\Filters\PostPublished;
use App\Nova\Lenses\MostTags;
use App\Nova\Metrics\PostsPerCategory;
use App\Nova\Metrics\PostsPerDay;
use App\Nova\Metrics\PostsPerMonth;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Rushughes\StringLimit\StringLimit;


class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Post';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public static $globallySearchable = true;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'body'
    ];

    public function title() {
      return $this->title . ' - ' . $this->category;
    }

    public function subtitle() {
      return 'Author: ' . $this->user->name;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            StringLimit::make('Title')->rules([
              'required'
            ])->max(150),
            Trix::make('Body')->rules([
              'required'
            ]),
            DateTime::make('Publish At')
              ->hideFromIndex()
              ->rules('after_or_equal:today'),
            DateTime::make('Publish Until')
              ->hideFromIndex()
              ->rules('after_or_equal:publish_at'),
            Boolean::make('Is Published'),
              // ->canSee(function($request) {
              //   return $request->user()->can('publish_post', $this);
              //   //return false;
              // }),
            Select::make('Category')->options([
              'tutorials' => 'Tutorials',
              'news' => 'News',
            ])->hideWhenUpdating()
              ->rules('required'),
            BelongsTo::make('User')->rules('required'),
            BelongsToMany::make('Tags')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
          (new PostsPerDay)->width('full'),
          (new PostsPerMonth)->width('1/2'),
          (new PostsPerCategory)->width('1/2'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
          new PostPublished,
          new PostCategories
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [
          new MostTags
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
          (new PublishPost)->canSee(function ($request) {
            return true;
          })->canRun(function ($request, $post) {
            return $post->id === 2;
          })
        ];
    }
}
