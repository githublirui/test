<?php
class  ThriftHelloServer {
	public function  start($serviceName,$host,$port) {
		$pipes = array();
		$dir = dirname(__FILE__);
		$zk_connect="192.168.2.202:2181";
		$cmd="java -jar -Dservice.host=".$host." -Dservice.port=".$port." -Dservice.announce=zk!".$zk_connect."!";
		$cmd=$cmd.$serviceName."!1 ".$dir."/finagle.hello_service.deploy.jar";
		var_dump($cmd);
		$this->proc = proc_open($cmd, array(),$pipes );
	}
	
	public function stop() {
		$result = proc_terminate( $this->proc );
		var_dump("Server stop result:" . $result);
	}
}
?>