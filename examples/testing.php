<?php

declare(strict_types=1);

use Bluesky\Bluesky;
use Carbon\Carbon;

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

$feedGenerator = $client->bsky()->feed()->getFeedGenerator('at://did:plc:z72i7hdynmk6r22z27h6tvur/app.bsky.feed.generator/whats-hot');
var_dump($feedGenerator);

$feedGenerators = $client->bsky()->feed()->getFeedGenerators([
    'at://did:plc:z72i7hdynmk6r22z27h6tvur/app.bsky.feed.generator/whats-hot',
    'at://did:plc:jfhpnnst6flqway4eaeqzj2a/app.bsky.feed.generator/for-science',
]);
var_dump($feedGenerators);

$likes = $client->bsky()->feed()->getLikes('at://did:plc:dg5diaehkfj3c66spqqvf7dp/app.bsky.feed.post/3lavri2jxhc24');
var_dump($likes);

$listFeed = $client->bsky()->feed()->getListFeed('at://did:plc:3ond7kebhvszgzsqo5llyipd/app.bsky.graph.list/3lawjh5t6pa2f');
var_dump($listFeed);

$postThread = $client->bsky()->feed()->getPostThread('at://coulb.com/app.bsky.feed.post/3lawmrlrrjk23');
var_dump($postThread);

$posts = $client->bsky()->feed()->getPosts([
    'at://did:plc:avuq73eppt7qxyxsnou6afcl/app.bsky.feed.post/3lawmrlrrjk23',
    'at://did:plc:ounoudlvca7fujj4hy4hec3c/app.bsky.feed.post/3lawuwuhnek2t',
]);
var_dump($posts);

$quotes = $client->bsky()->feed()->getQuotes('at://did:plc:epouc7jtubjdv64mum274hv2/app.bsky.feed.post/3layv3ui35c27');
var_dump($quotes);

$suggestedFeeds = $client->bsky()->feed()->getSuggestedFeeds();
var_dump($suggestedFeeds);

$timeline = $client->bsky()->feed()->getTimeline();
var_dump($timeline);

$searchPosts = $client->bsky()->feed()->searchPosts('php', sort: 'latest', since: Carbon::yesterday());
var_dump($searchPosts);

$unreadCount = $client->bsky()->notification()->getUnreadCount();
var_dump($unreadCount);

$followers = $client->bsky()->graph()->getFollowers('did:plc:3ond7kebhvszgzsqo5llyipd');
var_dump($followers);

$list = $client->bsky()->graph()->getList('at://did:plc:3ond7kebhvszgzsqo5llyipd/app.bsky.graph.list/3lawjh5t6pa2f');
var_dump($list);
