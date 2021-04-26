# RoiChamp API - PHP Client

A PHP wrapper for the RoiChamp REST API. Easily interact with the RoiChamp REST API securely using this library.

## Installation

```
composer require roichamp/php-client dev-main
```

## Getting started

To get your API key contact us on office@roichamp.com


## Client class

```php
$roichamp = new \RoiChamp\Client([
    'token' => 'API_KEY'
]);
```

## Client methods

### Subscriber Upsert

```php
$roichamp->upsertSubscriber([
    'email' => 'office@roichamp.com',
    'first_name' => 'Roi',
    'last_name' => 'Champ',
    'phone' => '+381123123',
    'metaData' => [
        'source' => 'google'
    ],
])
```

#### Arguments

| Params       | Type     | Required | Description                 |
|--------------|----------|----------|-----------------------------|
| `email`      | `string` | yes      | Subscriber email address.   |
| `first_name` | `string` | no       | Subscriber first name.      |
| `last_name`  | `string` | no       | Subscriber last name.       |
| `phone`      | `string` | no       | Subscriber phone number.    |
| `metaData`   | `array`  | no       | Key-value custom meta data. |

### Subscriber Unsubscribe

```php
$roichamp->unsubscribe('office@roichamp.com')
```

#### Arguments

| Params       | Type     | Required | Description                 |
|--------------|----------|----------|-----------------------------|
| `email`      | `string` | yes      | Subscriber email address.   |



### Category Upsert

```php
$roichamp->upsertProduct([
    'identity' => '42141',
    'title' => 'Product title',
    'image' => '...',
    'permalink' => '...',
    'suggest' => 1,
    'metaData' => [
        'foo' => 'bar'
    ],
])
```


### Product Upsert

```php
$roichamp->upsertProduct([
    'identity' => '14214',
    'title' => 'Product title',
    'price' => 199,
    'price_promo' => 99,
    'currency' => 'USD',
    'image' => '...',
    'permalink' => '...',
    'content' => 'Product description',
    'suggest' => 1,
    'categoryIdentities' => ['42141'],
    'metaData' => [
        'foo' => 'bar'
    ],
])
```

### Send transactional email

```php
$roichamp->sendEmail([
    'identity' => 'reset_password',
    'to' => 'office@roichamp.com',
    'params' => [
        'reset_url' => '...',
    ],
])
```
#### Arguments

| Params       | Type     | Required | Description                      |
|--------------|----------|----------|----------------------------------|
| `identity`   | `string` | yes      | Email identity.                  |
| `email`      | `string` | yes      | Subscriber email address.        |
| `params`     | `array`  | no       | Key-value custom email params.   |


## Release History

- 2021-04-26 - 1.0.0 - Pre-release.
