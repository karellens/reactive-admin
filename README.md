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