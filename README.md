# MediaWiki Banner Monitor

A small CLI application for monitoring banners defined by a MediaWiki instance and sending
notifications if something is wrong.

## Installation

PHP 5.4 or above is needed.

Get [http://getcomposer.org/](Composer) if it's not already installed.

Change into the root directory and run

    composer install

## Usage

Change into the root directory and run

    php monitor

This will output a list of available commands.

## Running the tests

Change into the root directory and run

    phpunit