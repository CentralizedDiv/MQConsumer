<?php
require_once ('./autoload.php');
$producer = new Producer();
$producer->addMessage(3, 'Message_TO_ACCEPT');