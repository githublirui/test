<?php
/**
 *
 * @author: zhaoweiguo
 * @date: 2014-06-12
 *
 * @brief: 使用thrift协议进行http通信
 */
require_once FINAGLE_BASE."/exception/SF_Exception.php";

class SF_THttpClient
{
    var $socket = null;
    var $accelerated = false;
    /**
     *
     * @param unknown $transportFactory
     * @param unknown $host
     * @param unknown $port
     * @param int $timeout in milliseconds
     */
    public function __construct($transportFactory, $host, $port, $path, $timeout,$readTimeout) {
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->timeout = $timeout;
        $this->readTimeout = $readTimeout;
        $this->transportFactory = $transportFactory;
    }

    public function setAccelerated($accelerated) {
        $this->accelerated=true;
    }

    public function connect() {
        try{
            $this->socket = new THttpClient($this->host, $this->port, $this->path);
            $this->socket->setTimeoutSecs($this->timeout);

            /* @tobechecked
            $this->socket->setSendTimeout($this->timeout);
            $this->socket->setRecvTimeout($this->readTimeout);
            */

            $this->transport = new TBufferedTransport( $this->socket, 1024, 1024 );

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
