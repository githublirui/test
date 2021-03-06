<?php

/**
 * 通过商品分类名获取商品分类
 */
function getFlByName($name) {
    $fl_r = mysql_query("select * from fl where cname='" . $name . "' and pid=3");
    if ($fl_r) {
        return mysql_fetch_assoc($fl_r);
    } else {
        return FALSE;
    }
}

/**
 * 通过省名获取省
 */
function getProvinceByName($name) {
    $province_r = mysql_query("select * from province where name='" . $name . "'");
    if ($province_r) {
        return mysql_fetch_assoc($province_r);
    } else {
        return FALSE;
    }
}

/**
 * 通过省名获取省
 */
function getCityByName($name) {
    $city_r = mysql_query("select * from city where name='" . $name . "'");
    if ($city_r) {
        return mysql_fetch_assoc($city_r);
    } else {
        return FALSE;
    }
}

/**
 * 通过省ID,市名获取市
 */
function getCityByNameAndProId($name, $pro_code) {
    $city_r = mysql_query("select * from city where name='" . $name . "' and provincecode='" . $pro_code . "'");
    if ($city_r) {
        return mysql_fetch_assoc($city_r);
    } else {
        return FALSE;
    }
}

/**
 * 编码转换
 * @param type $str_arr
 * @return type
 */
function Utf8ToGbk($str_arr) {
    if (is_array($str_arr)) {
        foreach ($str_arr as $key => $value) {
            if (is_array($value)) {
                $str_arr[$key] = Utf8ToGbk($value);
            } else {
                $str_arr[$key] = iconv("UTF-8", "GBK", $value);
            }
        }
    } else {
        $str_arr = iconv("UTF-8", "GBK", $str_arr);
    }
    return $str_arr;
}

/**
 * 获取注册用户唯一码
 */
function getHyUniqueCode() {
    $result = '';
    $code = substr(md5(uniqid(rand(), true)), 0, 10);
    $select_sql = "select count(*) as num from hy where code='" . $code . "'";

    $resouce = mysql_query($select_sql);
    while ($row = mysql_fetch_assoc($resouce)) {
        if ($row['num'] <= 0) {
            $result = $code;
        } else {
            $result = getHyUniqueCode();
        }
    }
    return $result;
}

function insertData($data_row) {
    #判断是否存在
    $select_hy_exist_sql = "select count(*) as num from `zgjw`.hy where usr='" . $data_row['username'] . "' or gsmc= '" . $data_row['username'] . "'";
    $select_num = mysql_fetch_assoc(mysql_query($select_hy_exist_sql));
    if ($select_num['num'] > 0) {
        return;
    }

    $preg = '/^[a-zA-Z0-9_+.-]+\@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,4}$/i';
    if (!$data_row['email'] || preg_match_all($preg, $data_row['email'], $match_email) == 0) {
        $data_row['email'] = 'office@chhome.cn';
    }

    $true_psw = substr(md5(uniqid(rand(), true)), 0, 6);
    $uid = uc_user_register($data_row['username'], $true_psw, $data_row['email']);

    if ($uid > 0) {
        $pic = substr(md5(uniqid(rand(), true)), 0, 10);
        rename('./logo/' . $data_row['logo'] . '.jpg', './logo/' . $pic . '.jpg');

        $sql = "insert into `zgjw`.hy (`code`,`usr`,`lxr`,`pwd`,`truepwd`,`pic`,`regrq`,`lx`,`email`,`tel`,`sh`,`gsmc`,`gsdz`,`province`,`city`,`yzbm`,`s1`,`collect_flag`)
         values ('" . getHyUniqueCode() . "','" . $data_row['username'] . "','" . $data_row['lxr'] . "','" . md5($true_psw) . "','" . $true_psw . "','upload/logo/" . $pic . ".jpg','" . date('Y-m-d H:i:s', time()) . "',1,'" . $data_row['email'] . "','" . $data_row['tel'] . "',1,
             '" . $data_row['gsmc'] . "','" . $data_row['detail_address'] . "','" . $data_row['province']['code'] . "','" . $data_row['city']['code'] . "','" . $data_row['postal_code'] . "','" . $data_row['pro_type']['id'] . "',5);";

        mysql_query($sql);
        $id = mysql_insert_id();
        file_put_contents('./insert_id.txt', $id . "    " . $data_row['username'] . "\n", FILE_APPEND);
        echo '.';
        flush();
    }
}
