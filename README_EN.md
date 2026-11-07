# laravel-whois

<p align="center">
    <a href="https://github.com/larvatecn/laravel-whois/actions/workflows/php.yml"><img src="https://github.com/larvatecn/laravel-whois/actions/workflows/php.yml/badge.svg" alt="Build Status"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/v/stable" alt="Stable Version"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/downloads" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/larva/laravel-whois"><img src="https://poser.pugx.org/larva/laravel-whois/license" alt="License"></a>
</p>

A Laravel WHOIS query extension powered by [io-developer/php-whois](https://github.com/io-developer/php-whois), with support for domain parsing, structured results, and raw WHOIS responses.

## Requirements

- PHP 8.2 or higher
- Laravel 11, 12, or 13
- PHP JSON extension

## Installation

```bash
composer require larva/laravel-whois
```

The package supports Laravel package auto-discovery, so you do not need to register the service provider or facade manually.

## Usage

Import the facade in the class where you want to perform queries:

```php
use Larva\Whois\Whois;
```

### Structured Lookup

```php
$info = Whois::lookup('www.example.com');
```

Returns an array on success, or `null` when structured information cannot be retrieved. The array contains the following fields:

| Field | Description |
| --- | --- |
| `parser_type` | Response parser type |
| `name` | Domain name |
| `registrar` | Registrar |
| `owner` | Owner information |
| `whois_server` | WHOIS server |
| `states` | Domain status list |
| `name_servers` | Nameserver list |
| `dnssec` | DNSSEC information |
| `creation_date` | Registration date as an `Illuminate\Support\Carbon` instance |
| `expiration_date` | Expiration date as an `Illuminate\Support\Carbon` instance |
| `updated_date` | Last update date as an `Illuminate\Support\Carbon` instance |
| `response_raw` | Raw WHOIS response |

### Full Result Object

```php
use Iodev\Whois\Modules\Tld\TldInfo;

/** @var TldInfo|null $info */
$info = Whois::lookupInfo('example.com');
```

This method returns a `TldInfo` object or `null`, providing access to the complete result fields and raw response from the parsing library.

### Raw Response

```php
$raw = Whois::lookupRaw('example.com');
```

### Domain Parsing

Lookup methods accept a registrable domain, subdomain, or URL string and automatically extract the registrable domain:

```php
$domain = Whois::getDomain('https://www.example.com/path');
// example.com

$domain = Whois::getDomain('www.example.co.uk');
// example.co.uk
```

## Exception Handling

- Invalid domains may throw `Larva\Whois\IllegalDomainException` or `Pdp\CannotProcessHost`.
- WHOIS server connection, response, or parsing failures may throw `Iodev\Whois\Exceptions\WhoisException` or one of its subclasses.

Query results depend on the data returned by the corresponding WHOIS server. Ensure that your network can access the relevant WHOIS services when performing live queries.

## Testing

```bash
composer test
```

Some tests query live WHOIS servers and require a working network connection.
