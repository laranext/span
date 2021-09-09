<p align="center">
<a href="https://packagist.org/packages/laranext/span"><img src="https://img.shields.io/packagist/dt/laranext/span" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laranext/span"><img src="https://img.shields.io/packagist/v/laranext/span" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laranext/span"><img src="https://img.shields.io/packagist/l/laranext/span" alt="License"></a>
</p>

## Introduction - Span

Laranext Span is module based approach, restful code separation, plug & play.

code separation like `routes`, `controllers`, `models`, `resources`, `views`, `migrations`

for example we have admin package and it will load with `/admin` key.

let's have a try.

## Getting Started

To install through Composer, by run the following command:

```bash
composer require laranext/span
```

then run install command to publish config file.

```bash
php artisan span:install
```

Create span package with your package name

```bash
php artisan span:package admin
```

if you will not choose any stub then default laravel package will be create.

after creating package we need to register in our `config/span.php` providers.

```php
'providers' => [
    'admin' => Admin\AdminServiceProvider::class,
],
```
then visit `/admin` in the browser.

## Generate Comands

it will work like default laravel artisan make commands.

the only difference is after class name we need package name too.

#### Controller

```bash
# Generate a controller class...
php artisan span:controller PhotoController admin

# Generate a resource controller class...
php artisan span:controller PhotoController admin --resource

# Generate a model and resource controller class...
php artisan span:controller PhotoController admin --resource --model=Photo

# Generate an api controller class...
php artisan span:controller Api/PhotoController admin --api

# Generate a invokable controller class...
php artisan span:controller ShowHomepage admin --invokable
```

#### Model

```bash
# Generate a model class...
php artisan span:model Flight admin

# Generate a model and a migration class...
php artisan span:model Flight admin --migration

# Generate a model and a FlightController class...
php artisan span:model Flight admin --controller
```

#### Migration

```bash
php artisan span:migration create_flights_table admin
```

## Why

why i created this over `laranext`, because laranext is little advance and not native for laravel developers.

`Span` is the same concept used by the `laranext` but it's more close to native `laravel`.

we can say it's only separation of code if we needed, it's totally optional.

`laranext` is still under production and not available publicly at the moment.

## Credits

- [Muhammad Ahsan Abrar](https://github.com/ahsanabrar)

## License

Laranext Span is open-sourced software licensed under the [MIT license](LICENSE.md).
