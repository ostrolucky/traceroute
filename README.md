# Detect unused routes

<br>


`traceroute` monitors usage of your application's routes, then shows you unused routes. You might want to do this in order to be able to get rid of unused controllers.
 
It's inspired by projects such as Symfony's [symfony-route-usage](https://github.com/migrify/symfony-route-usage), Laravel's [route-usage](https://github.com/julienbourdeau/route-usage/) and Rails's [traceroute](https://github.com/amatsuda/traceroute).
 
It differs from them by being less opinionated and having no dependencies, thanks to SOLID and decoupled design.
Framework integrations come as optional bridges, currently shipping with bridge compatible with Symfony 5/6/7.

## Install

```bash
composer require ostrolucky/traceroute
```

### Symfony framework installation

Register bridge/bundle in your `config/bundles.php`:

```php
return [
    Ostrolucky\Traceroute\Bridge\Symfony\TraceRouteBundle::class => ['prod' => true],
];
```

## Usage

Most of the time library happily monitors route usage in background automatically, recording this information in database table.

You are free to query this table yourself, however what you cannot do is cross-check this table which contains used routes with all routes defined in your application. That's what following command is for:

```bash
bin/console ostrolucky:unused-routes
```

It will simply output unused route names, meaning routes that are defined in your application, but were never accessed.

## Licensing

GPLv3 license. Please see [License File](LICENSE.md) for more information.
