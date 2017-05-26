<?php
/**
 * php运行shell脚本 
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL: http://svn.ganji.com:8888/svn/ganji_v3/trunk/cron/tuiguang/clear_active_offer.php $
 * @version              $Rev: 34275 $
 * @lastChangeBy         $LastChangedBy: yangyu $
 * @lastmodified         $LastChangedDate: 2010-06-08 01:21:21 +0800 (二, 2010-06-08) $
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Shell{
    protected $logpath =   "/tmp/";

    public static $stdin   = null;
    public static $stdout  = null;
    public static $stderror  = null;
    /** {{{ 执行shell脚本 exec($cmd)
     * @param string
     */
    public static function exec($cmd){
        self::cmd_log($cmd);
        $descriptorspec = array(
                0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                2 => array("pipe", "w") // stderr is a file to write to
                );
        $work_dir = null;

        $contents = array();
        $process = proc_open('/bin/bash', $descriptorspec, $pipes);
        if (is_resource($process)) {
            self::$stdin = $cmd;
            fwrite($pipes[0], $cmd);
            fclose($pipes[0]);

            self::$stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            self::$stderror = stream_get_contents($pipes[2]);
            fclose($pipes[2]);
        }
        $return_value = proc_close($process);
    }//}}}
    /** {{{ cmd_log
     */
    protected static function cmd_log($msg){
        $date = date("Y_m_d");
        $log_file = "/tmp/shell_" . $date . ".log";
        if(is_writeable($log_file)){
            error_log( date('[Y-m-d H:i:s]:') .$msg . "\n", 3, $log_file );
        }
    }//}}}
}
