<?php
namespace MessageQueue\examples;

use MessageQueue\examples\Store;

/* Example of producer */
class Producer {
    public function insertMessage($id, $message){
        try {
            //Uses the Store class to inser a message in the database, in this case at the file 'messages.json'
            $store = new Store();
            $store->insertMessage(array('id' => $id, 'message' => $message));
        }catch(\Exception $e){
            throw new \Exception('Error inserting message -> '.$e->getMessage());
        }        
    }
}