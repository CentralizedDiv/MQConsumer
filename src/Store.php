<?php
class Store implements IStore{
    public static function getMessages(){
        $messages = file_get_contents(__DIR__.'/assets/messages.json');
        return json_decode($messages, true);
    }

    public static function addMessage($newMessage) {
        $messages = self::getMessages();
        array_push($messages, $newMessage);
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public static function removeMessage($id) {
        $messages = self::getMessages();
        foreach($messages as $key => $msg) {
            if($msg['id'] === $id) {
                unset($messages[$key]);
                $messages = array_values($messages);
            }
        }
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public static function changeMessage($msgToChange) {
        $messages = self::getMessages();
        foreach($messages as $key => $msg) {
            if($msg['id'] === $msgToChange['id']) {
                $messages[$key] = $msgToChange;
            }
        }
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }

    public static function setMessages($messages) {
        file_put_contents(__DIR__.'/assets/messages.json', json_encode($messages));
    }
}