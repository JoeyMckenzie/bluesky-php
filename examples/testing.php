<?php

declare(strict_types=1);

use Bluesky\Bluesky;

require_once __DIR__.'/../vendor/autoload.php';

/**
 * This file can be used to test the client instance manually, simply add a `.env` file with your credentials.
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$username = $_ENV['BLUESKY_USERNAME'];
$password = $_ENV['BLUESKY_PASSWORD'];
$client = Bluesky::clientWithSession($username, $password);
$publicClient = Bluesky::publicClient();

$originalAccessJwt = $client->accessJwt;
$originalRefreshJwt = $client->refreshJwt;

assert($client->accessJwt !== null);
assert($client->refreshJwt !== null);

$refreshed = $client->refreshSession();

assert($refreshed->accessJwt !== null);
assert($refreshed->refreshJwt !== null);
assert($refreshed->accessJwt !== $originalAccessJwt);
assert($refreshed->refreshJwt !== $originalRefreshJwt);

$likes = $client->feed()->getActorLikes('joeymckenzie.tech');
var_dump($likes);

$result = $client->feed()->getAuthorFeed('joeymckenzie.tech');
var_dump($result);
