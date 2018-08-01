<?php
interface IStore {
    
    public static function getMessages();
    
    public static function addMessage($newMessage);
    
    public static function removeMessage($id);
    
    public static function changeMessage($msgToChange);
    
    public static function setMessages($messages);
}