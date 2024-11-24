<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nette\Utils\Helpers;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class Websocket extends Command
{

    protected $signature = 'ws:run';

    protected $description = 'Run websocket-server';

    public function handle()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new \App\Helpers\Websocket()
                )
            ),
            8080
        );

        $server->run();
    }
}
