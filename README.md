# Laravel + Typeahead Country/City

This small demo application introduced feature of populating cities on country selection and populating country on city selection :)

The current set of countries and cities can be seen from below array.

```
    [
        ['country' => 'USA', 'city' => 'New York City'],
        ['country' => 'USA', 'city' => 'New Jersey'],
        ['country' => 'USA', 'city' => 'Chicago'],
        ['country' => 'USA', 'city' => 'Dallas'],

        ['country' => 'Canada', 'city' => 'Otowa'],
        ['country' => 'Canada', 'city' => 'Alberta'],

        ['country' => 'UK', 'city' => 'London'],
        ['country' => 'UK', 'city' => 'Bradford'],
        ['country' => 'UK', 'city' => 'Hereford'],
    ]
```

## Setup

For setup do following:

- Copy `.env.example` to `.env`
- And execute below commands


```
php artisan key:generate
composer install
php artisan serve
```

- Now visit http://127.0.0.1:8000 to see the application running

## Requirements

- PHP 7.4 or above
- Composer 2.x or above
