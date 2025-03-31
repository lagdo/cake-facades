<!-- [![Build Status](https://github.com/lagdo/cake-facades/actions/workflows/test.yml/badge.svg?branch=main)](https://github.com/lagdo/cake-facades/actions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lagdo/cake-facades/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/lagdo/cake-facades/?branch=main)
[![StyleCI](https://styleci.io/repos/418488513/shield?branch=main)](https://styleci.io/repos/418488513)
[![codecov](https://codecov.io/gh/lagdo/cake-facades/branch/main/graph/badge.svg?token=HERKC60CC1)](https://codecov.io/gh/lagdo/cake-facades) -->

[![Latest Stable Version](https://poser.pugx.org/lagdo/cake-facades/v/stable)](https://packagist.org/packages/lagdo/cake-facades)
[![Total Downloads](https://poser.pugx.org/lagdo/cake-facades/downloads)](https://packagist.org/packages/lagdo/cake-facades)
[![License](https://poser.pugx.org/lagdo/cake-facades/license)](https://packagist.org/packages/lagdo/cake-facades)

Facades for CakePHP services
============================

With this package, CakePHP services can be called using service facades, with static method syntax.

It is a simpler alternative to passing services as parameters in the constructors of other classes, or using lazy services.
It will be especially interesting in the case when a class depends on many services, but calls some of them only occasionally.

### Installation

Install the package with `composer`.

```bash
composer require lagdo/cake-facades
```

Load the `Lagdo\Cake\Facades\FacadesPlugin` plugin in the `src/Application.php` file.

```php
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        ...

        // Load more plugins here
        $this->addPlugin(\Lagdo\Cake\Facades\FacadesPlugin::class);
    }
```

### Usage

A service facade inherits from the `Lagdo\Facades\AbstractFacade` abstract class, and implements the `getServiceIdentifier()` method, which must return the id of the corresponding service in the service container.

```php
namespace App\Facades;

use App\Services\MyService;
use Lagdo\Facades\AbstractFacade;

/**
 * @extends AbstractFacade<MyService>
 */
class MyFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return MyService::class;
    }
}
```

The methods of the `App\Services\MyService` service can now be called using the `App\Facades\MyFacade` facade, like this.

```php
class TheService
{
    public function theMethod()
    {
        MyFacade::myMethod();
    }
}
```

Instead of this.

```php
class TheService
{
    /**
     * @var MyService
     */
    protected $myService;

    public function __construct(MyService $myService)
    {
        $this->myService = $myService;
    }

    public function theMethod()
    {
        $this->myService->myMethod();
    }
}
```

The `@extends AbstractFacade<MyService>` phpdoc will prevent errors during code analysis with [PHPStan](https://phpstan.org/), and allow code completion on calls to service facades in editors.

### Getting the service instance

The `instance()` method of a service facade returns the instance of the linked service.

```php
class TheService
{
    public function theMethod()
    {
        $service = MyFacade::instance();
        $service->myMethod();
    }
}
```

### The `Lagdo\Facades\ServiceInstance` trait

By default, each call to a service facade method will also call the service container.
The service instance can be saved in the facade after the first call to the service container, using the `Lagdo\Facades\ServiceInstance` trait.
The next calls with return the service instance without calling the service container.

```php
namespace App\Facades;

use App\Services\MyService;
use Lagdo\Facades\AbstractFacade;
use Lagdo\Facades\ServiceInstance;

/**
 * @extends AbstractFacade<MyService>
 */
class MyFacade extends AbstractFacade
{
    use ServiceInstance;

    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return MyService::class;
    }
}
```

> [!IMPORTANT]
> The `Lagdo\Facades\ServiceInstance` trait *must* be defined in the final service facade class, and not inherited by a service facade.

The service container is called only once in this example code.

```php
    MyFacade::myMethod1(); // Calls the service container
    MyFacade::myMethod2(); // Doesn't call the service container
    MyFacade::myMethod1(); // Doesn't call the service container
```

### Provided facades

Some service facades are included by default in this package.

#### Logger

This facade requires that the `Psr\Log\LoggerInterface` id is defined and bound to the logger in the service container.

```php
use Lagdo\Facades\Logger;

Logger::info($message, $vars);
```

Contribute
----------

- Issue Tracker: github.com/lagdo/cake-facades/issues
- Source Code: github.com/lagdo/cake-facades

License
-------

The package is licensed under the 3-Clause BSD license.
