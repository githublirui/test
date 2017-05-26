<?php


if(!pdo_fieldexists('xhw_pingtu_user', 'countlimit')) {
    pdo_query("ALTER TABLE ".tablename('xhw_pingtu_user')." ADD createtime INT(11) UNSIGNED DEFAULT NULL ;");
}


if(!pdo_fieldexists('xhw_pingtu_user', 'weid')) {
    pdo_query("ALTER TABLE ".tablename('xhw_pingtu_user')." ADD `weid` int(10) NOT NULL ;");
}








