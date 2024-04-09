# EDM SDK PHP

[![Build Status](https://travis-ci.org/priorist/ais-sdk-php.svg?branch=master)](https://travis-ci.org/priorist/edm-sdk-php)
![License](https://img.shields.io/github/license/priorist/edm-sdk-php)
![GitHub release (latest SemVer including pre-releases)](https://img.shields.io/github/v/release/priorist/edm-sdk-php?include_prereleases&sort=semver)
![PHP from Travis config](https://img.shields.io/travis/php-v/priorist/ais-sdk-php)

A PHP library to interact with the RESTful API of the [Education Manager](https://education-manager.de) (EDM).

## Install with Composer

```shell
docker compose run --rm composer install
```

## Usage

### Init client

```php
use Priorist\EDM\Client\Client;

$client = new Client('https://edm.example.com', 'CLIENT_ID', 'CLIENT_SECRET');

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

### Enrollments

#### Enroll for a given event

```php
use Priorist\EDM\Client\Rest\ClientException;

$enrollment = [
    'first_name'    => 'John',
    'last_name'     => 'Doe',
    'event'         => 4711,
    'price'         => 4712,
];

try {
    $enrollment = $client->enrollment->create($enrollment);
} catch (ClientException $e) {
    $errors = $e->getDetails(); // Contains errors for missing/invalid fields/values
}

echo $enrollment['id']; // Holds the resulting ID on success.
```

### Generic requests

If you do not find a suitable method of a given repository, you may use the more
generic methods `fetchCollection($params = [])` and `fetchSingle(int $id, array $params = [])`.

E.g. `$client->event->findUpcoming()` equals

```php
$client->event->fetchCollection([
    'ordering' => 'first_day',
    'first_day__gte' => date('Y-m-d'),
]);
```

You can even call any endpoint you like, even the ones without an actual repository:

```php
$client->getRestClient()->fetchCollection('events', [
    'ordering' => 'first_day',
    'first_day__gte' => date('Y-m-d'),
]);
```

## Class docs

Current PHPDocs can be viewed here: <https://priorist.github.io/edm-sdk-php/>

## Run tests

Enable `compose.override.yml` and run

```shell
docker compose run --rm test
```

XDebug support for Docker for Mac included.

To create and view a detailed, browsable test coverage report run

```shell
docker compose run --rm test tests --coverage-html test_results/coverage && open test_results/coverage/index.html
```

## Generate docs

```shell
docker compose run --rm docs && open docs/index.html
```

## Build *.phar archive

To use the SDK in legacy applications, you may build and include a *.phar package
in your application.

Download phar-composer first:

```shell
curl --location --output phar-composer.phar https://clue.engineering/phar-composer-latest.phar
```

Build the archive:

```shell
docker compose run --rm phar
```

To use the client, include the autoload of the archive:

```php
include 'edm-sdk.phar/vendor/autoload.php';
```
