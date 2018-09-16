# reactive-admin

### Installation

```bash
php artisan vendor:publish --provider="Lavary\Menu\ServiceProvider"
```

```bash
php artisan vendor:publish --provider="Karellens\ReactiveAdmin\ReactiveAdminServiceProvider"
```

Alternatively you can:

```bash
php artisan vendor:publish --provider="Karellens\ReactiveAdmin\ReactiveAdminServiceProvider" --tag=config --force
```

```bash
php artisan vendor:publish --provider="Karellens\ReactiveAdmin\ReactiveAdminServiceProvider" --tag=views --force
```

```bash
php artisan vendor:publish --provider="Karellens\ReactiveAdmin\ReactiveAdminServiceProvider" --tag=public --force
```

### Tune-up

```bash
php artisan make:middleware ReactiveAdminMenus

```
Be sure to also add the middleware to the `app\Http\Kernel.php`
```php
    protected $routeMiddleware = [
        //...
        'raa_menu' => \App\Http\Middleware\ReactiveAdminMenus::class,
    ];
```

Create sidebar menu:
```php
    public function handle($request, Closure $next)
    {
        $raa_uri = config('reactiveadmin.uri');

        \Menu::make('ReactiveAdminSidebar', function ($menu) use ($raa_uri) {
            $menu->add(__('reactiveadmin::reactiveadmin.dashboard'), ['url' => $raa_uri, 'class' => 'nav-item'])
                ->link->attr(['class' => 'nav-link']);

            $menu->add(__('Pages'), $raa_uri.'/pages')
                ->link->attr(['class' => 'nav-link']);

            $menu->add(__('Blocks'), $raa_uri.'/blocks')
                ->link->attr(['class' => 'nav-link']);
        });

        return $next($request);
    }
```


Example of ReactiveAdminResource config (you can put it in `ReactiveAdminMenus`):

```php
ReactiveAdmin::resource('pages', 'Page|Pages', function (ReactiveAdminResource $resource)
    {
        $resource
            ->setClass(\App\Page::class)
            ->setQuery(\App\Page::with(['ancestors', 'blocks']));

        $resource
            ->addColumn('id', '#')->sortable()
            // concat ancestors like breadcrumbs
            ->addColumn('ancestors')->wrapper(function ($value) use ($resource) {
                return '<span style="font-size: 0.7rem;">'.$value->sortBy('_lft')->map(function($v) use ($resource) {
                        return '<a href="'.$resource->getEditLink($v).'">'.$v->caption.'</a>';
                    })->implode('>').'</span>';
            })
            ->addColumn('caption', 'Caption')->sortable()
            // concat child Blocks and make it linkable
            ->addColumn('blocks', 'Blocks')->wrapper(function ($value) {
                return $value
                    ->sortBy('pivot.weight')
                    ->map(function($v){ return '<a href="/admin/blocks/'.$v->id.'/edit">'.$v->title.'</a>'; })
                    ->implode(', ');
            })
            ->addColumn('alias', 'Alias')->sortable()
            ->addColumn('keywords', 'Meta Keywords')
            ->addColumn('description', 'Meta Description');

        $resource
            ->addField('parent_id', 'Parent page', 'select')
                ->options(
                    ['0' => ''] + DB::table('pages')
                                    ->get()
                                    ->pluck('caption', 'id')
                                    ->toArray()
                )
            ->addField('template', 'Template', 'select')
                ->options([
                    'default' => 'Standard',
                    'list' => 'Standard (without right column)',
                    'full_width' => 'Full width',
                ])
            ->addField('title', 'Title')
            ->addField('caption', 'Caption')
            ->addField('alias', 'Alias')
                ->help('Synonym for URL. Example: <strong>`complectation`</strong> for page https://auto.com/pontiac/firebird/<strong>complectation</strong>')
            ->addField('content', 'Text', 'wysiwyg')
            ->addField('keywords', 'Meta Keywords')
                ->help('Keywords for a page separated by commas. Used by search engines.')
            ->addField('description', 'Meta Description', 'text')
                ->help('Text description for the page. Used by search engines.')
            ->addField('blocks', 'Blocks', 'relationspivot')
                ->formatter(function ($value) {
                    return $value->title;
                })
                ->options(DB::table('blocks')->pluck('title', 'id'))
                ->pivotFields(['region' => 'Region', 'weight' => 'Order']);
    });
```
