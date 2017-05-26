<?php
/**
 * @brief 写入文件
 * @param type $log 写入的文件
 * @param type $content 需要写入的字符串
 * @return null
 */
function writelog($log, $content){
    $fp = fopen($log, 'a+');
    fwrite($fp, $content);
    fclose($fp);
}

/**
 * 
 * @global SplFileObject $apache_fp 打开apache日志的资源
 * @global SplFileObject $nginx_fp 打开nginx日志的资源
 * @global string $converge_log 需要写入的文件
 * @param type $apache_line_number 从第几行开始读apache日志
 * @param type $nginx_line_number 从第几行开始读nginx日志
 * @param boolean $nginx_status nginx日志是否读到末尾
 * @return null
 * @brief 将日志文件按照时间归并
 *     打开apache文件, 一行一行读取
 *     每行读取的时候, 打开nginx文件, 将nginx每行的时间读出来, 与apache当前行对比
 *         时间小于apache当前行, 一直读取, 直到nginx的当前行时间大于apache当前行时间, 将读出来的nginx行和当前apache行写入文件
 *             并记录nginx的当前的行数line, 下次循环nginx日志, 从line行开始后读取
 *         时间大于apache当前行, break, 继续循环apache日志
 *     当apache日志全部读取完之后, 将nginx日志以后的行全部写入归并的文件中
 */
function convergelog($apache_line_number = 0, $nginx_line_number = 0, $nginx_status = true){
    global $apache_fp, $nginx_fp, $converge_log;
    
    $strings        = '';
    $apache_status  = true;

    $apache_fp->seek($apache_line_number);
    $apache_line = str_replace(array('\"\r\n\"', '\"\r\"', '\"\n\"'), "", $apache_fp->current());
    if(empty($apache_line)){
        $apache_status = false;
    }
    
    $apache_array   = explode('[', $apache_line);
    $apache_time    = strtotime($apache_array[0]);

    while($nginx_status){
        $nginx_fp->seek($nginx_line_number);
        $nginx_line = str_replace(array('\"\r\n\"', '\"\r\"', '\"\n\"'), "", $nginx_fp->current());
        if(empty($nginx_line)){
            $nginx_status = false;
        }
        $nginx_array    = explode('[', $nginx_line);
        $nginx_time     = strtotime($nginx_array[0]);
        if($nginx_time <= $apache_time && $apache_status !== false){
            $strings .= $nginx_line;
            $nginx_line_number = $nginx_line_number + 1;
        }else{
            if($apache_status === false){
                // apache日志到最后, 将nginx其他的日志写入
                $strings .= $nginx_line;
            }
            break;
        }
    }

    $apache_line_number = $apache_line_number + 1;

    writelog($converge_log, $strings . $apache_line);
    if($apache_status === false){
        return null;
    }
    convergelog($apache_line_number, $nginx_line_number, $nginx_status);
}

$apache_log     = 'apache.log';
$nginx_log      = 'nginx.log';
$converge_log   = 'coverge.log';
    
unlink($converge_log);

$apache_fp  = new SplFileObject($apache_log);
$nginx_fp   = new SplFileObject($nginx_log);

convergelog();
