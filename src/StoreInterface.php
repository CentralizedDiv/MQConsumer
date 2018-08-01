<?php
namespace MQConsumer\src;

/**
 * Interface Store
 *
 * Interface to provider a implementation of custom store for message queueing
 *
 */
interface StoreInterface {

    /**
	 * Get all or some messages from Database.
	 *
	 * @param array $filter filters to the selection
	 *
	 * @return array The messages
	 */
    public function getMessages($filter = array());
    
    /**
	 * Insert a message in Database.
	 *
	 * @param array $newMessage json with all properties of the message
	 */
    public function insertMessage($newMessage);
    
    /**
	 * Remove a message from Database
	 *
	 * @param array $id identifier of the message
     */
    public function removeMessage($id);
    
    /**
	 * Update a message from the Database.
	 *
	 * @param array $msgToChange json with all properties of the new message
	 */
    public function updateMessage($msgToChange);
    
    /**
	 * Set all the messages from the Database.
	 *
	 * @param array $messages array with all new messages
	 */
    public function setMessages($messages);
}
