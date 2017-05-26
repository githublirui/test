<?PHP
/**
 * Implements a memcache api queue
 * Author: caifeng, 2012/03/23
 *
 *
 */

class MemcacheQueueConnection {
    var $is_kestrel;
    var $memc;
    var $queue;
    var $in_transaction = false;
    public function __construct($config, $queue, $is_kestrel=true) {
/*    $config = array(
              'servers' => array('127.0.0.1:22133'),
              'debug'   => false,
              'compress_threshold' => 10240,
              'persistant' => true);
*/
        $this->memc = new Memcached( );
        if (is_array($config[0])) {
            $this->memc->addServers($config);
        } else {
            $this->memc->addServer( $config[0], $config[1] );
        }
        $this->memc->setOption(Memcached::OPT_COMPRESSION, 0);
        $this->memc->setOption(Memcached::OPT_CONNECT_TIMEOUT, 1 * 1000);
        $this->memc->setOption(Memcached::OPT_HASH, Memcached::HASH_CRC);

        $this->is_kestrel = $is_kestrel;
        $this->queue = $queue;
    }

    public function dequeue($timeout = false, $reliable=false) {
        if( $timeout !== false && ! $this->is_kestrel )
            throw new Exception( "Only kestrel supports timeout get" );
        if( $reliable ) {
            $key = $this->queue . "/close/open";
            $this->in_transaction = true;
            }
        else
            $key = $this->queue;

        if( $timeout )
            $key = $key . '/t=' . $timeout*1000;

        $record = $this->memc->get($key);
        #var_dump( $key, $record );
        return $record;
    }

    public function abort() {
        if( $this->in_transaction ) {
            $this->memc->get( $this->queue . "/abort" );
            $this->in_transaction = false;
        }
    }

    public function set( $record ) {
        $this->memc->set( $this->queue , $record );
    }
}

