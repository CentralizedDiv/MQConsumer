<?php
class Producer {
    public function addMessage($id, $message){
        try {
            $store = 'Store'; 
            $store::addMessage(array('id' => $id, 'message' => $message));
        }catch(\Exception $e){
            throw new \Exception('Error inserting message -> '.$e->getMessage());
        }        
    }
}