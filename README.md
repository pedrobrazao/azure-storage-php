# Azure Storage PHP

## requirements

- PHP 8.0+
- Composer 2+
- Node.JS  8.0+
- NPM

## Setup

Install PHP dependencies:

`composer install`

Install Node.JS modules:

`npm install`

> Checkout `dumper` Git branch in case you need all PHP and Node.JS dependencies already installed.

## Usage

Start Azurite:

`node_modules/.bin/azurite --silent --location var/storage/ --debug var/storage/debug.log`

Run PHP web server:

`php -S localhost:8000 -t public/`

Go to http://localhost:8000/