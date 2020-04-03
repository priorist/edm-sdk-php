# AIS SDK PHP

A PHP library to interact with the RESTful API of the Academic Information System (AIS).


## Install with Composer

```shell
docker-compose run --rm composer install
```


## Usage

### Init client

```php
use Priorist\AIS\Client\Client;

$client = new Client('https://ais.example.com', 'CLIENT_ID', 'CLIENT_SECRET');
```

### Events

#### Single event for a given ID

```php
$event = $client->event->findById(4711);

if ($event !== null)
    echo $event['event_base_name'] . "\n";
}
```

#### List of upcoming events

```php
$upcomingEvents = $client->event->findUpcoming();

foreach ($upcomingEvents as $event) {
    echo $event['event_base_name'] . "\n";
}
```


### Event locations

#### Single location for a given ID

```php
$location = $client->eventLocation->findById(4711);

if ($location !== null)
    echo $location['name'] . "\n";
}
```

#### List of all locations

```php
$locations = $client->eventLocation->findAll();

foreach ($locations as $location) {
    echo $location['name'] . "\n";
}
```


## Generic requests

If you do not find a suitable method of a given repository, you may use the more
generic methods `queryCollection($params = [])` and `querySingle(int $id, array $params = [])`.

E.g. `$client->event->findUpcoming()` equals

```php
$client->event->queryCollection([
    'ordering' => 'first_day',
    'first_day__gte' => date('Y-m-d'),
]);
```


## Run tests

Enable `docker-compose.override.yml` and run

```shell
docker-compose run --rm test
```

XDebug support for Docker for Mac included.

To create and view a detailed, browsable test coverage report run

```shell
docker-compose run --rm test --coverage-html test_results/coverage && open test_results/coverage/index.html
```


## Generate docs

```shell
docker-compose run --rm docs
```


## Build *.phar archive

To use the SDK in legacy applications, you may build an include a *.phar package
in you application.

Download phar-composer first:

```shell
curl --location --output phar-composer.phar https://clue.engineering/phar-composer-latest.phar
```

Build the archive:

```shell
docker-compose run --rm phar
```

To use the client, include the autoload of the archive:

```php
include 'ais-sdk.phar/vendor/autoload.php';
```
