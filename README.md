# RESTnova

[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](http://php.net)
![GitHub](https://img.shields.io/github/license/rdurica/restnova)

RESTnova is a lightweight and modern PHP client for making HTTP requests, specifically designed for communicating with
RESTful APIs. It provides an intuitive interface for sending GET, POST, PUT, DELETE, and other HTTP requests
effortlessly.

## Features

- Simple and intuitive API for sending HTTP requests
- Supports various HTTP methods: GET, POST, PUT, DELETE, etc.
- Modern and lightweight design
- Required PHP >= 8.3

## Installation

You can install RESTnova via [Composer](https://getcomposer.org).

```shell
composer require rdurica/restnova
```
## Usage
```php
// Create client. All setters are optional.
$client = Client::create()
    ->addHeaderItem('User-Agent', 'example')
    ->setTimeout(10)
    ->setFollowRedirects(false)
    ->build();

// Execute request.
$response = $client->get('https://api-example.robbyte.net/auth');

// Available methods:
// - get
// - post
// - delete
// - head
// - patch
```
## Contributing

If you would like to contribute to this project, please fork the repository and create a pull request. We welcome all
contributions, including bug fixes, new features, and documentation improvements.

## License

This project is licensed under the terms of the MIT license.