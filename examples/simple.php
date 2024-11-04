<?php

declare(strict_types=1);

use Bluesky\Bluesky;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$username = (string) getenv('BLUESKY_USERNAME');
$password = (string) getenv('BLUESKY_PASSWORD');

$client = Bluesky::client();

$session = $client->session()->createSession($username, $password);
var_dump($session);
