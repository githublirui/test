<?php
    /**
     * 公交数据查询url转码
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    if (isset($_GET['keyword']))
    {
        if (strlen($_GET['keyword']) === 0)
        {
            echo '{keyword : 0}';
            exit();
        }
        else
        {
            echo '{keyword : "' . urlencode(iconv('utf-8', 'gbk', $_GET['keyword'])) . '"}';
            exit();
        }
    }
    else if (isset($_GET['keyword1']) && isset($_GET['keyword2']))
    {
        if (strlen($_GET['keyword1']) === 0 || strlen($_GET['keyword2']) === 0)
        {
            echo '{keyword1 : 0, keyword2 : 0}';
            exit();
        }
        else
        {
            echo '{keyword1 : "' . urlencode(iconv('utf-8', 'gbk', $_GET['keyword1'])) . '", keyword2 : "' . urlencode(iconv('utf-8', 'gbk', $_GET['keyword2'])) . '"}';
            exit();
        }
    }
    else
    {
        echo '{keyword : 0}';
        exit();
    }