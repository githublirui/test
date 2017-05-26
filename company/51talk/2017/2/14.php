<?php

//匿名函数
function Benchmark() {
    foreach (func_get_args() as $function) {
        $st = microtime(true);
        call_user_func($function);
        $et = microtime(true);
        echo sprintf("Time: %f", $et - $st) . '<br />';
    }
}

Benchmark(function() {
    echo 1;
}, function() {
    echo 2;
});
?>
