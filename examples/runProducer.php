<?php
require_once ('../__autoload.php');
use MessageQueue\examples\Producer;

$producer = new Producer();
$producer->insertMessage(3, 'Message_TO_ACCEPT');