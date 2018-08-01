<?php
require_once ('../__autoload.php');
use MessageQueue\src\Consumer;

$consumer = new Consumer('\MessageQueue\examples\Store');

/* Example of callbackConsumer function */
$callbackConsumer = function(&$msg) {
    
    /* Example of requeue function */
    $callbackRequeue = function(&$msgs) {
        rsort($msgs);
        return $msgs;
    };
    
    if(strpos($msg['message'], 'TO_ACCEPT') !== false) {
        $msg['consumer']->accept($msg);
        var_dump($msg['message']); 
    }else {
        $msg['message'] .= '_TO_ACCEPT';
        $msg['consumer']->reject($msg, $callbackRequeue);
    }   
};


$consumer->consume($callbackConsumer);