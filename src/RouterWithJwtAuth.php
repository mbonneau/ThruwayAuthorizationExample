<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/JwtAuthenticationProvider.php';

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router();

$transportProvider = new RatchetTransportProvider("127.0.0.1", 9090);

$router->registerModule(new \Thruway\Authentication\AuthenticationManager());

$authorizationManager = new \Thruway\Authentication\AuthorizationManager('realm1');
$router->registerModule($authorizationManager);

// don't allow anything by default
$authorizationManager->flushAuthorizationRules(false);

// allow sessions in the sales role to subscribe to sales.numbers
$salesRule = new stdClass();
$salesRule->role   = "sales";
$salesRule->action = "subscribe";
$salesRule->uri    = "sales.numbers";
$salesRule->allow  = true;

$authorizationManager->addAuthorizationRule([$salesRule]);

// allow sessions in the bookkeeping role to publish to sales.numbers
$bookkeepingRule = new stdClass();
$bookkeepingRule->role   = "sales";
$bookkeepingRule->action = "publish";
$bookkeepingRule->uri    = "sales.numbers";
$bookkeepingRule->allow  = true;

$authorizationManager->addAuthorizationRule([$bookkeepingRule]);

$router->addInternalClient(new JwtAuthenticationProvider(['realm1'], 'example_key')); // CHANGE YOUR KEY

$router->addTransportProvider($transportProvider);

$router->start();
