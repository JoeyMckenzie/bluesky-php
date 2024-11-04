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
$client = Bluesky::client();

// Option 1, we can manually a session and forward the token
$session = $client->session()->createSession($username, $password);
var_dump($session);

$profile = $client->actor()->getProfile($username, $session->accessJwt);
var_dump($profile);

// OR option 2, create a new session through the client instance
$newSession = $client->newSession($username, $password);
var_dump($newSession);

$profile = $client->actor()->getProfile($username);
var_dump($profile);
