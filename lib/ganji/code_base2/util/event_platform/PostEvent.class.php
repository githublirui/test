<?php
/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /code_base2/util/event_platform/PostEvent.class.php
 * @author        dujian@ganji.com
 * @date          2011-03-30
 *
 */
include_once CODE_BASE2 . '/util/event_platform/inc/event_proxy/TSReportEvent.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/protocol/TProtocol.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/transport/TTransport.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php';
include_once $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php';

/*
 * @class: PostEvent
 * @PURPOSE:  帖子event件处理的静态类
 *
 */

class PostEvent {
    
    /// @bref 新帖子被创建event请求
    /// @param $data array
    ///     @li @c city        城市id
    ///     @li @c category    大类id
    ///     @li @c major_category    小类id
    ///     @li @c post_id    帖子id
    /// @param $op_name string 可选参数,用来表示谁来处理的
    /// @param $source string 可选参数，来自哪里的请求
    
    public static function createTriggerPost($data, $op_name='ms_user_new' , $source = 'post') {
        self::triggerPostEvent(EventAction::kNewContent, $data, $op_name , $source);
    }
    
    /// @bref 帖子被修改evnet请求
    /// @param $data array
    ///     @li @c city        城市id
    ///     @li @c category    大类id
    ///     @li @c major_category    小类id
    ///     @li @c post_id    帖子id
    /// @param $op_name string 可选参数,用来表示谁来处理的
    /// @param $source string 可选参数，来自哪里的请求

    public static function updateTriggerPost($data, $op_name='ms_user_edit' , $source = 'post') {
        self::triggerPostEvent(EventAction::kUpdateContent, $data, $op_name , $source);
    }
    
    /// @bref 帖子被删除evnet请求
    /// @param $data array
    ///     @li @c city        城市id
    ///     @li @c category    大类id
    ///     @li @c major_category    小类id
    ///     @li @c post_id    帖子id
    /// @param $op_name string 可选参数,用来表示谁来处理的
    /// @param $source string 可选参数，来自哪里的请求

    public static function deleteTriggerPost($data, $op_name='ms_user_delete' , $source = 'post') {
        if(is_array($data)) {
            $count = count($data);
            $data = implode("\t",array_map("implode",array_pad(array(),$count,"\t"),$data));
        }
        self::triggerPostEvent(EventAction::kDelContent, $data, $op_name , $source);
    }
    
    public static function triggerPostEvent($event_act, $data, $op_name='ms_user_edit' , $source = 'post') {
        if ( ! is_string($data) ) {
            //$data = json_encode($data);
            $data = implode("\t",$data);
        }
        //self::log($event_act.":".$data);
        $event = new OrgEvent(array(
            'source'    => $source,
            'id'        => 0,
            'act'        => $event_act,
            'timeout'    => 600,
            'content'    => $data,
            'op_name'   => $op_name
        ));
        try {
            $proxy_conf = GanjiConfig::$event_proxy;
            $transport = new TSocket($proxy_conf['host'], $proxy_conf['port']);
            $transport->open();
            $protocol = new TBinaryProtocol($transport);
            $client= new TSReportEventClient($protocol, $protocol);
            $client->ReportEvent($event);
            $transport->close();
        } catch (Exception $e) {
            trigger_error('EventPlatform: ' . $e->getMessage(), E_USER_WARNING);
        }
    }

    public static function log($msg){
        $date = date("Y-m-d H:i:s");
        $filedate = date("Y_m_d");
        $msg = "[".$date."]:".$msg."\n";

        $logfile = "/tmp/post_event" .$filedate.".log";
        error_log($msg,3,$logfile);
    }
}
