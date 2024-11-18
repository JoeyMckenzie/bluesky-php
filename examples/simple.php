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

// Option 1, we can manually a session and forward the token
$session = $client->session()->createSession($password);
var_dump($session);

$profile = $client->actor()->getProfile($username);
var_dump($profile);

// OR option 2, create a new session through the client instance
$newSession = $client->newSession($password);
var_dump($newSession);

$profile = $client->actor()->getProfile($username);
var_dump($profile);

// Create a post
$post = $client->app()->graph()->post('This post was brought to you by PHP. Working on yet another Bluesky client for PHP, heavily inspired Nuno\'s OpenAI client. Coming to a Packagist feed near you... 🤠');
