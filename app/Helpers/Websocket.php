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
        $this->rooms = [];
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
//        echo $msg . PHP_EOL;
//        print_r($from->resourceId);
        $msg = json_decode($msg);

        if ($msg->flag === 'service') {
            // Сохраняем только необходимые данные
            $this->rooms[$msg->room] = [$msg->user, $msg->buddy];
            $this->users[$msg->user] = $from->resourceId; // Сохраняем номер подключения для каждого пользователя.
        } else if ($msg->flag === 'chat') {
            print_r($this->users);
            print_r($this->rooms);
            if (isset($this->rooms[$msg->room])) {
                $recipients = $this->rooms[$msg->room]; // Получаем список пользователей в комнате
                foreach ($recipients as $man) {
                    if (isset($this->users[$man]) && $from->resourceId !== $this->users[$man]) {
                        foreach($this ->clients as $client){
                            if($client->resourceId == $this->users[$man]){
                                $client->send(json_encode($msg));
                                echo "Сообщение отправлено на id - $msg->recipient";
                            }
                        }
                    } else {
                        echo 'Пользователь не в сети.' . PHP_EOL;
                    }
                }
            } else {
                echo "Room does not exist: {$msg->room}\n";
            }
        }
//        foreach($this->clients as $client){
////            $client->send(json_encode($msg));
//            echo $client->resourceId . PHP_EOL;
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
