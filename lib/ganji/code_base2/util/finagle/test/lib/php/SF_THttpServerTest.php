#!/usr/bin/env php
<?php
require_once dirname(__FILE__) . '/../../../SF_Bootstrap.php';

if (php_sapi_name() == 'cli') {
    ini_set("display_errors", "stderr");
}

$GEN_DIR = realpath(dirname(__FILE__).'/..').'/gen-php';
require_once $GEN_DIR.'/shared/SharedService.php';
require_once $GEN_DIR.'/shared/shared_types.php';
require_once $GEN_DIR.'/tutorial/Calculator.php';
require_once $GEN_DIR.'/tutorial/tutorial_types.php';
error_reporting(E_ALL);


class CalculatorHandler implements CalculatorIf {
    protected $log = array();

    public function ping() {
        error_log("[server]ping()");
    }

    public function add($num1, $num2) {
        error_log("[server]add({$num1}, {$num2})");
        return $num1 + $num2;
    }

    public function calculate($logid, $w) {
        error_log("calculate({$logid}, {{$w->op}, {$w->num1}, {$w->num2}})");
        switch ($w->op) {
            case \tutorial\Operation::ADD:
                $val = $w->num1 + $w->num2;
                break;
            case \tutorial\Operation::SUBTRACT:
                $val = $w->num1 - $w->num2;
                break;
            case \tutorial\Operation::MULTIPLY:
                $val = $w->num1 * $w->num2;
                break;
            case \tutorial\Operation::DIVIDE:
                if ($w->num2 == 0) {
                    $io = new \tutorial\InvalidOperation();
                    $io->what = $w->op;
                    $io->why = "Cannot divide by 0";
                    throw $io;
                }
                $val = $w->num1 / $w->num2;
                break;
            default:
                $io = new \tutorial\InvalidOperation();
                $io->what = $w->op;
                $io->why = "Invalid Operation";
                throw $io;
        }

        $log = new \shared\SharedStruct();
        $log->key = $logid;
        $log->value = (string)$val;
        $this->log[$logid] = $log;

        return $val;
    }

    public function getStruct($key) {
        error_log("getStruct({$key})");
        // This actually doesn't work because the PHP interpreter is
        // restarted for every request.
        //return $this->log[$key];
        return new \shared\SharedStruct(array("key" => $key, "value" => "PHP is stateless!"));
    }

    public function zip() {
        error_log("[server]zip()");
    }

};

header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
    echo "\r\n";
}

$handler = new CalculatorHandler();
$processor = new CalculatorProcessor($handler);

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);

$transport->open();
$processor->process($protocol, $protocol);
$transport->close();
