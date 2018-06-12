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

Create sidebar menu:

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


Example of RAAConfig array:

```php
<?php

/**
 * Page model config
 */

return [
    'model' => App\Page::with('blocks'),
    'class_name' => 'App\Page',

    'title' => 'Страницы',

    /**
     * The display columns
     */
    'fields' => [
        'id'		=> [
            'title'		=> '#',
        ],
        'title' => [
            'title' => 'Навание',
        ],
        'blocks' => [
            'title'     => 'Блоки',
            'wrapper'   => function ($value) {
                return $value->map(function($v){ return '<a href="/admin/blocks/'.$v->id.'/edit">'.$v->title.'</a>'; })->implode(', ');
            }
        ],
        'alias' => [
            'title' => 'Алиас',
        ],
        'keywords' => [
            'title' => 'Meta keywords',
        ],
        'description' => [
            'title' => 'Meta description',
        ],
    ],

    /**
     * The filter set
     */
    'filters' => [
        'id',
    ],

    /**
     * The editable fields
     */
    'edit_fields' => [
        'title' => [
            'title' => 'Название',
            'type' => 'string',
        ],
        'alias' => [
            'title' => 'Алиас',
            'type' => 'string',
        ],
        'content' => [
            'title' => 'Текст',
            'type' => 'wysiwyg',
        ],
        'keywords' => [
            'title' => 'Meta keywords',
            'type' => 'string',
        ],
        'description' => [
            'title' => 'Meta description',
            'type' => 'text',
        ],
    ],

];
```
