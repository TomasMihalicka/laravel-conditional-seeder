# Migration conditions in Laravel database Seeder
==========================

PHP trait for Laravel providing methods for conditional database seeding.


## Installation
Add `phirational/laravel-conditional-seeder` to require section of `composer.json`.

```json
{
    "require": {
        "phirational/laravel-conditional-seeder": "~1.0"
    }
}
```

Then run `composer install` or `composer update`.

Next:

* Add `use Phirational\LaravelConditionalSeeder\ConditionalSeeder;` before your `DatabaseSeeder` class declaration.
* Add `use ConditionalSeeder;` trait right after your `DatabaseSeeder` class header.

```php
<?php

use Phirational\LaravelConditionalSeeder\ConditionalSeeder;

class DatabaseSeeder extends Seeder
{
    use ConditionalSeeder;
    
    public function run()
    {
        ...
    }
}
```

## Methods
###isInLastMigrations
Determine if migration (or migrations) is in last migration batch

```php
/**
 * @param string|array $migrations Migration name or array of migrations
 * @param bool $partialMatch Allow if just one matching migration is enough
 *
 * @return bool
 */
function isInLastMigrations($migrations, $partialMatch = false)
```

###isInRanMigrations
Determine if migration (or migrations) is between ran migrations

```php
/**
 * @param string|array $migrations Migration name or array of migrations
 * @param bool $partialMatch Allow if just one matching migration is enough
 *
 * @return bool
 */
function isInRanMigrations($migrations, $partialMatch = false)
```

## Usage
After extending your `DatabaseSeeder` with `ConditionalSeeder` trait, you can check if some migrations were ran with methods `isInLastMigrations` and `isInRanMigrations`.

####Simple check
```php
public function run()
{
    // Call ExampleTableSeeder when was '2014_05_15_001618_foo' in last migration batch
    if ($this->isInLastMigrations('2014_05_15_001618_foo'))
    {
        $this->call('ExampleTableSeeder');
    }

    // Call ExampleTableSeeder whenever was '2014_05_15_002342_bar' ran
    if ($this->isInRanMigrations('2014_05_15_002342_bar'))
    {
        $this->call('ExampleTableSeeder');
    }
}
```

####Checking multiple migrations with optional partial match
```php
public function run()
{
    // Call ExampleTableSeeder when was '2014_05_15_001618_foo' AND '2014_05_15_002342_bar' in last migration batch
    if ($this->isInLastMigrations(array('2014_05_15_001618_foo', '2014_05_15_002342_bar'), false))
    {
        $this->call('ExampleTableSeeder');
    }
    
    // Call ExampleTableSeeder when was '2014_05_15_001618_foo' OR '2014_05_15_002342_bar' in last migration batch
    if ($this->isInLastMigrations(array('2014_05_15_001618_foo', '2014_05_15_002342_bar'), true))
    {
        $this->call('ExampleTableSeeder');
    }
}
```
