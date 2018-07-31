<?php
require_once ('./autoload.php');
$consumer = new Consumer();

$callbackConsumer = function(&$msg) {
    
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