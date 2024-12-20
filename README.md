<div align="center" style="padding-top: 2rem;">
    <img src="images/bluesky_logo.svg" height="150" width="150" alt="logo"/>
    <div style="display: inline-block; padding-top: 2rem">
        <img src="https://img.shields.io/packagist/v/joeymckenzie/bluesky-php.svg?style=flat-square" alt="packgist downloads" />
        <img src="https://img.shields.io/github/actions/workflow/status/joeymckenzie/bluesky-php/run-tests.yml?branch=main&label=tests&style=flat-square" alt="tests ci" />
        <img src="https://img.shields.io/github/actions/workflow/status/joeymckenzie/bluesky-php/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square" alt="packgist downloads" />
        <img src="https://img.shields.io/packagist/dt/joeymckenzie/bluesky-php.svg?style=flat-square" alt="packgist downloads" />
    </div>
</div>

# Bluesky PHP

> 🚧 This package is still in early development, with no major releases at the moment. Use with precaution!

A Bluesky PHP client compatible with your HTTP client of choice. The goal of this project is to provide a simple,
easy-to-use PHP HTTP client to interact with [Bluesky's API](https://docs.bsky.app/), providing methods for calling
both the authenticated and public endpoints.

### Current API coverage status

> ℹ️ Current progress: 26/165 endpoints covered

The Bluesky API surface is fairly large, and we'll be doing our best to cover as many of the endpoints as possible
to provide a friendly client experience. In terms of currently covered resources:

#### App namespace

- [x] Actor
- [x] Feed
- [ ] Graph
- [ ] Notification
- [ ] Video

#### Chat namespace

- [ ] Actor
- [ ] Convo
- [ ] moderation

#### AT Proto

- [ ] Admin
- [ ] Identity
- [ ] Repo
- [ ] Server (only session create/refresh done as of now)
- [ ] Sync

#### Tools namespace

- [ ] Communication
- [ ] Moderation
- [ ] Server
- [ ] Setters
- [ ] Signature
- [ ] Team

## Bluesky PHP in action

```php
<?php

declare(strict_types=1);

use Bluesky\Bluesky;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// By default, the client assumes we're going to authenticate as a valid user
$username = $_ENV['BLUESKY_USERNAME'];
$password = $_ENV['BLUESKY_PASSWORD'];

// Construct the client using the default builder with no customizations
$client = Bluesky::client($username);

// It's also possible to use the public API as well
$publicApi = Bluesky::publicClient();

// Next, create a new session for the API
$session = $client->newSession($password);
var_dump($session);

$profile = $client->bsky()->actor()->getProfile($username);
var_dump($profile);

// Create a post
$post = $client->bsky()->feed()->post('This post was brought to you by PHP. Working on yet another Bluesky client for PHP, heavily inspired Nuno\'s OpenAI client. Coming to a Packagist feed near you... 🤠');
```

## Getting started

To get started, install Bluesky from Packagist

```shell
$ composer install joeymckenzie/bluesky-php
```

Within your code, instantiate a new instance of the client:

```php
$client = Bluesky::client('username.bsky.social');

// To use the client, you'll need to create a new session that'll grab some JWTs for authentication
$client->newSession('password123');

// Or, build a new client with a session
$clientWithSession = Bluesky::clientWithSession('username.bsky.social', 'password123');

// Or, using the public API client that doesn't require authentication
$publicClient = Bluesky::publicClient(); 
```

## Status

The API surface of Bluesky's API is fairly large encompassing some odd 160ish different endpoints. A complete list can
be found within the [TODO](TODO.md) list, containing simple tracking of endpoints that have been implemented and those
yet to be implemented.

## Testing

To run tests

```shell
$ composer run test
```

Bluesky PHP uses [Pest](https://pestphp.com/) for testing, where each endpoint contains a test that:

- Verifies the call as we expect to Bluesky
- Verifies the properties on the response

You'll find test data within the [fixtures](tests/Fixtures) folder, container stubs with randomly generated
fake data mimicking data received from the API at various endpoints.
