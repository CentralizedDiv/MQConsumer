<?php
namespace MQConsumer\examples;
use MQConsumer\src\StoreInterface;

/* Example of Store class using file */
class Store implements StoreInterface{
    public function getMessages($filter = array()){
        $messages = file_get_contents(__DIR__.'/assets/messages.json');
        return json_decode($messages, true);
    }

    public function insertMessage($newMessage) {
        $messages = $this->getMessages();
        array_push($messages, $newMessage);
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public function removeMessage($id) {
        $messages = $this->getMessages();
        foreach($messages as $key => $msg) {
            if($msg['id'] === $id) {
                unset($messages[$key]);
                $messages = array_values($messages);
            }
        }
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public function updateMessage($msgToChange) {
        $messages = $this->getMessages();
        foreach($messages as $key => $msg) {
            if($msg['id'] === $msgToChange['id']) {
                $messages[$key] = $msgToChange;
            }
        }
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public function setMessages($messages) {
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }
}