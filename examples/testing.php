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

$originalAccessJwt = $client->getAccessJwt();
$originalRefreshJwt = $client->getRefreshJwt();

assert($client->getAccessJwt() !== null);
assert($client->getRefreshJwt() !== null);

$refreshed = $client->refreshSession();

assert($refreshed->getAccessJwt() !== null);
assert($refreshed->getRefreshJwt() !== null);
assert($refreshed->getAccessJwt() !== $originalAccessJwt);
assert($refreshed->getRefreshJwt() !== $originalRefreshJwt);

$feedGenerator = $client->feed()->getFeedGenerator('at://did:plc:z72i7hdynmk6r22z27h6tvur/app.bsky.feed.generator/whats-hot');
var_dump($feedGenerator);

$feedGenerators = $client->feed()->getFeedGenerators([
    'at://did:plc:z72i7hdynmk6r22z27h6tvur/app.bsky.feed.generator/whats-hot',
    'at://did:plc:jfhpnnst6flqway4eaeqzj2a/app.bsky.feed.generator/for-science',
]);
var_dump($feedGenerators);

$likes = $client->feed()->getLikes('at://did:plc:dg5diaehkfj3c66spqqvf7dp/app.bsky.feed.post/3lavri2jxhc24');
var_dump($likes);
