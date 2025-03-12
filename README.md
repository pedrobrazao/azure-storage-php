# Azure Storage PHP

This repository illustrates the usage of Azure Storage features using PHP as the backend environment.

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

Adjust the project settings accordingly your needs by copying configuration to a local file:

`cp config/settings.php config/settings.local.php`

## Usage

Start Azurite:

`node_modules/.bin/azurite --silent --location var/storage/ --debug var/storage/debug.log`

> Check Azurite documentation for details about configuration options:
> https://learn.microsoft.com/en-us/azure/storage/common/storage-use-azurite?tabs=npm%2Cblob-storage

Run PHP web server:

`php -S localhost:8000 -t public/`

Go to http://localhost:8000/