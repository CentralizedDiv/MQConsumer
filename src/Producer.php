<?php
class Producer {
    public function showMessages(){
        var_dump(Store::getMessages());
    } 

    public function addMessage($id, $message){
        try {
            Store::addMessage(array('id' => $id, 'message' => $message));
        }catch(\Exception $e){
            throw new \Exception('Error inserting message -> '.$e->getMessage());
        }        
    }
}