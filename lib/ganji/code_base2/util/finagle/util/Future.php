<?php
	interface Future {
		function apply($timeout) ;
		function within($timer,$timeout,$callback);
		function addEventListener($eventListener);
	}
?>