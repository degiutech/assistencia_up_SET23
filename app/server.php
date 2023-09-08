<?php

// require './vendor/autoload.php';

// use Ratchet\Server\EchoServer;

// $app = new Ratchet\App('localhost', 9980);
// $app->route('/echo', new EchoServer, ['*']);
// $app->run();

use Chat\Chat;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require '../vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

$server->run();
