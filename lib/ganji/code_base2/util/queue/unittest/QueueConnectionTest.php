<?php
require dirname(__FILE__) . "/../QueueConnection.class.php";

class MyQueueConnection extends QueueConnection {
    public function buildInsertSql($array_data) {
        return "insert into {$this->queueName} (v1,v2) values({$array_data[0]},\"{$array_data[1]}\")";
    }
}

class QueueConnectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp() {
        //$this->queue_in = new MyQueueConnection( self::$qa , "test", "my_queue");
        //$this->queue_out = new MyQueueConnection( self::$qa , "test", "my_queue");
        
        //$conn = $this->queue_out->getConnection();
        //DBMysqlNamespace::execute ( $conn, 'truncate my_queue');
    }
    
    //public function testEnqueue() {
        //$this->assertTrue( $this->queue_in->enqueue( array(1,"test1")) );
        //$out = $this->queue_out->dequeue();
        //$this->assertEquals( array( "v1" => 1, "v2" => "test1" ), $out );
    //}
}
