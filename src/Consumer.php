<?php
namespace MQConsumer\src;

class Consumer {
    
    protected $storeClass;

    /**
     * Constructor
     * 
     * @param string    $storeClass     Namespace of the implemented StoreInterface class.
     */
    public function __construct($storeClass) {
        $this->storeClass = new $storeClass();
    }
    
    /**
     * Infinite listener to the database, looking for new messages.
     *
     * @param string    $callback       Callable function to handle the current message in the queue.
     * @param array     $filter         Filter to use when get the messages in every iteration of the inifinite loop.
     */
    public function consume($callback, $filter = array()) {
        //Garbage Collector
        gc_enable();
        while(true) {
            $queue = $this->storeClass->getMessages($filter);
            if(is_array($queue)) {
                foreach($queue as $msg) {
                    $msg['consuming'] = true;
                    $msg['consumer'] = $this;
                    call_user_func_array($callback, array(&$msg));    
                    unset($msg['consumer']);
                    //if the message wasnt removed, update it
                    if(!isset($msg['removed']))
                        $this->storeClass->updateMessage($msg);
                    
                    //If the queue was ordered in last iteration, restart the loop
                    if($msg['requeued'] === true) {
                        break;
                    }
                }
            }
            //Garbage Collector
            gc_collect_cycles();
        }
    }

    /**
     * Accept the current message.
     *
     * @param array     $msg            The accepted Message.
     * @param mixed     $requeue        Option to 'reorder' the queue.
     */
    public function accept(&$msg, $requeue = true) {
        $msg['accepted'] = true;
        $msg['rejected'] = false;
        $msg['consuming'] = false;
        
        //If true, remove; if callable, call the requeue function 
        if($requeue === true){
            $this->storeClass->removeMessage($msg['id']);
            $msg['removed'] = true;
            $msg['requeued'] = false;  
        }else if(is_callable($requeue)) {
            $this->callRequeueFunction($requeue);
            $msg['requeued'] = true;     
        }else {
            $msg['requeued'] = false;      
        }
    }

    /**
     * Reject the current message.
     *
     * @param array     $msg            The rejected Message.
     * @param mixed     $requeue        Option to 'reorder' the queue.
     */
    public function reject(&$msg, $requeue = true) {
        $msg['accepted'] = false;
        $msg['rejected'] = true;
        $msg['consuming'] = false;
        
        //If true, remove; if callable, call the requeue function 
        if($requeue === true){
            $this->storeClass->removeMessage($msg['id']);
            $msg['removed'] = true;
            $msg['requeued'] = false;  
        }else if(is_callable($requeue)) {
            $this->callRequeueFunction($requeue);
            $msg['requeued'] = true;
        }else {
            $msg['requeued'] = false;  
        }
    }

    /**
     * Call the requeue function. The requeue function must return an array of messages.
     *
     * @param string    $requeue        Callable function to handle the queue.
     */
    private function callRequeueFunction($requeue) {
        $messages = $this->storeClass->getMessages();
        $requeuedMessages = call_user_func_array($requeue, array(&$messages));  
        $this->storeClass->setMessages($requeuedMessages);
    }
}
