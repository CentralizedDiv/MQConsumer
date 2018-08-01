# MQ Consumer
PHP Lib to implement a 'Message Queue Consumer' that provides a big liberty to manage the queue. You can sort and reorder the messages after consuming it.

## How to use
This is how you can use MQ Consumer:
1. Implements Store class.
```php
class Store implements StoreInterface{
    public function getMessages($filter = array()){
        .
        .
        .
    }

    .
    .
    .
}
```
2. Create a Producer class that comunicate with you Store Class, to insert and manipulate messages.
```php
class Producer {
    public function insertMessage($id, $message){
        try {
            $store = new Store();
            $store->insertMessage(array('id' => $id, 'message' => $message));
        }catch(\Exception $e){
            throw new \Exception('Error inserting message -> '.$e->getMessage());
        }        
    }
}
```
3. Create a callback function to handle consumed messages
```php
$callbackConsumer = function(&$msg) {
    
    /* Example of requeue function */
    $callbackRequeue = function(&$msgs) {
        rsort($msgs);
        return $msgs;
    };
    
    /* Do whatever you want with the message, after that, accept or reject */
    $msg['message'] .= '_TO_ACCEPT';
    $msg['consumer']->reject($msg, $callbackRequeue);   
};
```
4. Start the Consumer
```php
//You have to give your Store Class
$consumer = new Consumer('\MessageQueue\examples\Store');
$consumer->consume($callbackConsumer);
```

You can run the consumer in a terminal, and the producer in another, to check the consuming in 'real time'.

Check the folder 'assets' to this and other examples of usage.