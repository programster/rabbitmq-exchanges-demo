<?php
require_once __DIR__ . '/vendor/autoload.php';

define('RABBITMQ_HOST', 'rabbitmq.programster.org');
define('RABBITMQ_PORT', '5672');
define('RABBITMQ_USERNAME', 'guest');
define('RABBITMQ_PASSWORD', 'guest');
define('EXCHANGE_NAME', 'logs');


function logEvent()
{
    $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
        RABBITMQ_HOST, 
        RABBITMQ_PORT, 
        RABBITMQ_USERNAME, 
        RABBITMQ_PASSWORD
    );
    
    $channel = $connection->channel();
    
    # Create the exchange if it doesnt exist already.
    $channel->exchange_declare(
        EXCHANGE_NAME, 
        'fanout', # type
        false,    # passive
        false,    # durable
        false     # auto_delete
    );
    
    $data = "Event created!";    
    $msg = new \PhpAmqpLib\Message\AMQPMessage($data);
    $channel->basic_publish($msg, EXCHANGE_NAME);
    echo "Published: $data" . PHP_EOL;
    
    $channel->close();
    $connection->close();
}

logEvent();