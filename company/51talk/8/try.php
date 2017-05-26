<?php

//throw new Exception('用户手机号或密码错误');


$json = '{
    "status": 1,
    "info": "",
    "data": {
        "2016-08-16": {
            "17:00-19:00": {
                "2班": "白居易",
                "3班": "朱家晒"
            },
            "19:00-20:00": {
                "4班": "学生A",
                "5班": "学生B"
            }
        },
        "2016-08-17": {
            "17:00-19:00": {
                "2班": "白居易1",
                "3班": "朱家晒2"
            },
            "19:00-20:00": {
                "4班": "学生A",
                "5班": "学生B"
            }
        }
    }
}';
$re = json_decode($json, true);
$a = array(
    'status' => 1,
    'info' => '',
    'data' =>
    array(
        '2016-08-16' => array(
            'count' => 4,
            'data' => array(
                '17:00-19:00' => array(
                    'count' => 2,
                    'data' => array(
                        '2班',
                        '3班',
                    ),
                ),
                '19:00-20:00' => array(
                    'count' => 2,
                    'data' => array(
                        '4班',
                        '5班',
                    ),
                ),
            ),
        ),
        '2016-08-17' => array(
            'count' => 4,
            'data' => array(
                '17:00-19:00' => array(
                    'count' => 2,
                    'data' => array(
                        '2班',
                        '3班',
                    ),
                ),
                '19:00-20:00' => array(
                    'count' => 2,
                    'data' => array(
                        '4班',
                        '5班',
                    ),
                ),
            ),
        ),
    ),
);
var_dump(json_encode($a));
die;
?>
