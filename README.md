# MediaWiki Banner Monitor

A small CLI application for monitoring banners defined by a MediaWiki instance and sending
notifications if something is wrong.

[![Build Status](https://travis-ci.org/wmde/MediaWikiBannerMonitor.svg?branch=master)](https://travis-ci.org/wmde/MediaWikiBannerMonitor)
[![Code Coverage](https://scrutinizer-ci.com/g/wmde/MediaWikiBannerMonitor/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wmde/MediaWikiBannerMonitor/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wmde/MediaWikiBannerMonitor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wmde/MediaWikiBannerMonitor/?branch=master)

## Installation

PHP 5.3 or above is needed.

Get [http://getcomposer.org/](Composer) if it's not already installed.

Change into the root directory and run

    composer install

## Configuration

	cp conf/local-config.sample.inc.php conf/local-config.inc.php

	adjust settings in banners.sample.yml for bannercheck configuration

## Usage

Change into the root directory and run

    php monitor

This will output a list of available commands.

## Running the tests

Change into the root directory and run

    phpunit