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

// $client now works with global permission, e.g. to read events.

$events = $client->event->findUpcoming();

// To switch to permissions of a given user, e.g. to read participant data, call logIn
// with the user’s login name and password:

$accessToken = $client->logIn('USER_NAME', 'PASSWORD');

$client->event->findParticipating();

// You may store $accessToken in your session to re-use it later:

$client->setAccessToken($accessToken);
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


### Categories

#### Single category for a given ID

```php
$location = $client->category->findById(4711);

if ($category !== null)
    echo $category['name'] . "\n";
}
```

#### List of all categories

```php
$categories = $client->category->findAll();

foreach ($categories as $category) {
    echo $category['name'] . "\n";
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


### Lecturers

#### Single lecturer for a given ID

```php
$lecturer = $client->lecturer->findById(4711);

if ($lecturer !== null)
    echo $lecturer['name'] . "\n";
}
```

#### List of all lecturers

```php
$lecturers = $client->lecturer->findAll();

foreach ($lecturers as $lecturer) {
    echo $lecturer['name'] . "\n";
}
```


### Tags

#### Single tag for a given ID

```php
$lecturer = $client->tag->findById(4711);

if ($tag !== null)
    echo $tag['name'] . "\n";
}
```

#### List of all tags

```php
$tags = $client->tag->findAll();

foreach ($tags as $tag) {
    echo $tag['name'] . "\n";
}
```


### Generic requests

If you do not find a suitable method of a given repository, you may use the more
generic methods `queryCollection($params = [])` and `querySingle(int $id, array $params = [])`.

E.g. `$client->event->findUpcoming()` equals

```php
$client->event->queryCollection([
    'ordering' => 'first_day',
    'first_day__gte' => date('Y-m-d'),
]);
```

You can even call any endpoint you like, even the ones without an actual repository:

```php
$client->getRestClient()->queryCollection('events', [
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
docker-compose run --rm test tests --coverage-html test_results/coverage && open test_results/coverage/index.html
```


## Generate docs

```shell
docker-compose run --rm docs && open docs/index.html
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
