<?php
/**
 * Created by PhpStorm.
 * User: zhaoweiguo
 * Date: 14-6-16
 * Time: AM11:50
 */


use Thrift\Base\TBase;
use Thrift\Type\TType;
use Thrift\Type\TMessageType;
use Thrift\Exception\TException;
use Thrift\Exception\TProtocolException;
use Thrift\Protocol\TProtocol;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;

require_once dirname(__FILE__) . '/../../SF_Bootstrap.php';


if(php_sapi_name() == 'cli') {
    ini_set('display_errors', 'stderr');


}



class SF_ThriftHttpServerDemo implements \test\TestServiceIf {

    public function __construct() {
        echo "SF_ThriftHttpServer demo...";
    }

    public function getName($name) {
        return "abc";
    }

}

$path = $_SERVER["REQUEST_URI"];
if($path == '/test/thttpclient') {
    $demo = new SF_ThriftHttpServerDemo();
    $name = $demo->getName('key');
    echo $name;
} else {
    throw new Exception("unknow path");
}



/*
echo "<p>Welcome to PHP</p>";
$demo = new SF_ThriftHttpServerDemo();
$name = $demo->getName('key');
var_dump($name);

*/
