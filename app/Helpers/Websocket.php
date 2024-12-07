<?php
namespace App\Helpers;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Websocket implements MessageComponentInterface {
    protected $clients;
    protected $rooms; //'room'=>["id's"]
    protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo $msg . PHP_EOL;
        $msg = json_decode($msg);
        if($msg->flag === 'service'){
            $this->rooms[$msg->room] = [$msg->user, $msg->buddy];
            $this->users[$msg->user] = $from->resourceId;
        }else if($msg->flag === 'chat'){
            print_r($this->rooms);
            print_r($this->users);
            if(isset($this->rooms[$msg->room])) {
                foreach($this->rooms[$msg->room] as $man) {  //тут выдает ид пользователей в комнате
                    foreach ($this->clients as $client){
                        if(isset($this->users[$msg->recipient])){
                            if($client->resourceId == $this->users[$msg->recipient]){
                                $client->send(json_encode($msg));
                                echo "Сообщение отправленно на id - $msg->recipient";
                            }
                        }else{
                            echo 'Пользователь не в сети.'. PHP_EOL;
                        }

                    }

                }
            } else {
                echo "Room does not exist: {$msg->room}\n";
            }
        }

//        echo $msg . PHP_EOL;
//        foreach ($this->clients as $client) {
//            if ($from !== $client) {
//                $client->send($msg);
//            }
//        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
