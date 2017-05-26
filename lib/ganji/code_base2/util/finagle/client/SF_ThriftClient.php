<?php
/**
 *
 * User: tailor
 * Date: 14-3-19
 * Time: 下午2:44
 *
 * 2014-04-29 add accelerated
 *
 */
require_once FINAGLE_BASE."/exception/SF_Exception.php";

class SF_ThriftClient
{
    // 实际的thriftClient对象
    var $socket = null;
    var $accelerated = false;
    /**
     * 
     * @param unknown $transportFactory
     * @param unknown $host
     * @param unknown $port
     * @param int $timeout in milliseconds
     */
    public function __construct($transportFactory, $host, $port, $timeout,$readTimeout) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->readTimeout = $readTimeout;
        $this->transportFactory = $transportFactory;
    }

    public function setAccelerated($accelerated) {
        $this->accelerated=true;
    }

    public function connect() {
        try{
            $this->socket = new TSocket($this->host, $this->port);
            $this->socket->setSendTimeout($this->timeout);
            $this->socket->setRecvTimeout($this->readTimeout);

            $framedsocket = new TFramedTransport($this->socket, true, true);
            $this->transport = $framedsocket;

            if( $this->accelerated ) {
                $protocol = new TBinaryProtocolAccelerated($this->transport);
                $protocol_out = new TBinaryProtocol($this->transport);
            }
            else {
                $protocol = new TBinaryProtocol($this->transport);
                $protocol_out = $protocol;
            }
            $this->client = $this->transportFactory->getInnerClient($protocol, $protocol_out);
            $this->transport->open();
            return true;
        }
        catch(Exception $e){
            throw new SF_SocketException($e);
        }
    }

    public function __call($name, $arguments) {
        if( method_exists($this->client, $name)) {
            try {
                return call_user_func_array(array($this->client, $name), $arguments );
            }
            catch(TTransportException $e) {
                throw new SF_SocketTimeoutException($e);
            }
            catch(TException $e) {
                throw new SF_SocketException($e);
            }
            catch(Exception $e) {
                throw new SF_Exception($e);
            }
        }
        else {
            throw new BadMethodCallException();
        }
    }

    public function close() {
        if ($this->transport && $this->transport->isOpen()) {
            $this->transport->close();
        }
        $this->socket = null;
        $this->transport = null;
        $this->client = null;
    }
}
