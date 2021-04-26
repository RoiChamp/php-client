# RoiChamp API - PHP Client

A PHP wrapper for the RoiChamp REST API. Easily interact with the RoiChamp REST API securely using this library.

## Installation

```
composer require roichamp/php-client
```

## Getting started

For API credentials please contact us on office@roichamp.com


## Client class

```php
$roichamp = new Client('INSERT_API_KEY_HER');
```

## Client methods

### Subscriber Upsert

```php
$roichamp->upsertSubscriber($parameters = [])
```

#### Arguments

| Params       | Type     | Required | Description |
|--------------|----------|----------|-------------|
| `email`      | `string` | yes      |             |
| `first_name` | `string` | no       |             |
| `last_name`  | `string` | no       |             |
| `phone`      | `string` | no       |             |
| `metaData`   | `array`  | no       |             |


## Release History

- 2021-04-26 - 1.0.0 - First release.
