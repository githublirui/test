<?php
class SF_GanjiLoggingConfig extends GanjiLoggingConfig {
    public static $error_level_ttransportexception = 'TTransportException';
    public static $error_level_texception = 'TException';
    public static $error_level_exception = 'Exception';
    public static $error_level_badmethodcallexception = 'BadMethodCallException';

    public $enableScribe = false;
    public $recordMode = 2;
    public $logDir = '/tmp/';

    const BASE_CATEGORY = "finagle-php";

    const DEBUG = 1; // Most Verbose
    const INFO = 2; // ...
    const WARN = 3; // ...
    const ERROR = 4; // ...
    const FATAL = 5; // Least Verbose

    private static $PRIORITY = array(
        '' =>  self::WARN,
    );

    static function getConfig($category) {
        if( $category === FALSE ) {
            $scategory = self::BASE_CATEGORY;
        }
        else {
            $scategory = self::BASE_CATEGORY . '.' . $category;
        }

        if( isset(self::$PRIORITY[$category] )) {
            $priority = self::$PRIORITY[$category];
        }
        else {
            $priority = self::$PRIORITY[''];
        }

        return array( $scategory , $priority);
    }

    static function getBaseCategory() {
        return self::BASE_CATEGORY;
    }
}