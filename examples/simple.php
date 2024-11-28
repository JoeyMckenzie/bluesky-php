<?php

declare(strict_types=1);

use Bluesky\Bluesky;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// By default, the client assumes we're going to authenticate as a valid user

/** @var string $username */
$username = $_ENV['BLUESKY_USERNAME'];

/** @var string $password */
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
