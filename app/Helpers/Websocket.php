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

        if ($msg->flag === 'service') {
            // Сохраняем только необходимые данные
            $this->rooms[$msg->room] = [$msg->user, $msg->buddy];
            $this->users[$msg->user] = $from; // Сохраняем только объектов пользователей, которые активны.
        } else if ($msg->flag === 'chat') {
            if (isset($this->rooms[$msg->room])) {
                $recipients = $this->rooms[$msg->room]; // Получаем список пользователей в комнате
                foreach ($recipients as $man) {
                    // Проверяем, существует ли пользователь и не является ли он отправителем
                    if (isset($this->users[$man]) && $from !== $this->users[$man]) {
                        $this->users[$man]->send($msg);
                        echo "Сообщение отправлено на id - $msg->recipient";
                    } else {
                        echo 'Пользователь не в сети.' . PHP_EOL;
                    }
                }
            } else {
                echo "Room does not exist: {$msg->room}\n";
            }
        }
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
