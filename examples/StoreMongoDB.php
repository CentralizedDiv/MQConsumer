<?php
namespace MessageQueue\examples;
use MessageQueue\src\StoreInterface;
//use ...\Mongo;

/* Example of Store class using MongoDB */
class StoreMongoDB implements StoreInterface{

    public function __construct($server = 'MONGO_SERVER', $port = 'MONGO_PORT', $collection = 'COLLECTION_NAME', $dbName = 'DATABASE_NAME') {
        $this->mongo = new Mongo($server, $port, $dbName);
        $this->collection = $collection;
    }
    
    public function getMessages($filter = array()) {
        $messages = $this->mongo->find($this->collection, $filter);
        return $messages;
    }
    
    public function insertMessage($newMessage) {
        $this->mongo->insert($this->collection, $newMessage);   
    }
    
    public function removeMessage($id) {
        $this->mongo->remove($this->collection, array('_id' => $id));   
    }
    
    public function changeMessage($msgToChange) {
        $id = null;
        foreach($msgToChange as $key => $value) {
            if($key === '_id')
                $id = $value;
        }
        $this->mongo->update($this->collection, array('_id' => $id), $msgToChange);   
    }
    
    public function setMessages($messages) {  
        $currentMessages = $this->getMessages();
        $this->mongo->remove($this->collection, array());
        $this->mongo->insertMany($this->collection, $messages);
    }
    
}