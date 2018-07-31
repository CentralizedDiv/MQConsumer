<?php
class Consumer {
    public function consume($callback) {
        while(true) {
            $queue = Store::getMessages();
            if(is_array($queue)) {
                foreach($queue as $msg) {
                    $msg['consuming'] = true;
                    $msg['consumer'] = $this;
                    while($msg['consuming'] !== false) {
                        call_user_func_array($callback, array(&$msg));    
                    }

                    if(!isset($msg['removed']))
                        Store::changeMessage($msg);

                    if($msg['requeued'] === true) {
                        break;
                    }
                }
            }
        }
    }

    public function accept(&$msg, $requeue = true) {
        $msg['accepted'] = true;
        $msg['rejected'] = false;
        $msg['consuming'] = false;
        
        if($requeue === true){
            Store::removeMessage($msg['id']);
            $msg['removed'] = true;
            $msg['requeued'] = false;  
        }else if(is_callable($requeue)) {
            $this->callRequeueFunction($requeue);
            $msg['requeued'] = true;     
        }
    }

    public function reject(&$msg, $requeue = true) {
        $msg['accepted'] = false;
        $msg['rejected'] = true;
        $msg['consuming'] = false;
        
        if($requeue === true){
            Store::removeMessage($msg['id']);
            $msg['removed'] = true;
            $msg['requeued'] = false;  
        }else if(is_callable($requeue)) {
            $this->callRequeueFunction($requeue);
            $msg['requeued'] = true;
        }
    }

    private function callRequeueFunction($requeue) {
        $messages = Store::getMessages();
        $requeuedMessages = call_user_func_array($requeue, array(&$messages));  
        Store::setMessages($requeuedMessages);
    }
}
