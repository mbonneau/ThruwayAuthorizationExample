<?php

require_once __DIR__ . '/../vendor/autoload.php';

$authid = "joe";
$key = "example_key"; // CHANGE THIS

$token = array(
    "authid" => $authid,
    "authroles" => ["user", "sales"]
);

$jwt = \Firebase\JWT\JWT::encode($token, $key);

echo $jwt;