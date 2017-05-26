<?php
	interface  SF_LoadBalancer {
		/**
		 * 给定的服务地址，选定一个
		 * @param Array $addrs
		 */
		public function match($addrs);
	}
?>