<?php

/**
 * Class UnitLog
 *
 * Added by 赵卫国 just for testing
 */
class UnitLog {

    public static function info($context) {
        $file = "/tmp/finagle-php.log";

        file_put_contents($file, $context."\n", FILE_APPEND);
    }

}



