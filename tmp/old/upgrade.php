<?php

/**
 * sql���½ű�
 * cmd��  php upgrade.php folder ����
 * 
 */
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'zgjw';
$is_run_all = false; //�Ƿ�ȫ��ִ��

$conn = @mysql_connect($dbhost, $dbuser, $dbpw) or die('E010001'); // ��������Դ
@mysql_select_db($dbname) or die('E010002'); // ѡ�����ݿ�
@mysql_query("set names gbk");
$folder = $argv[1];
$version = isset($argv[2]) ? (int) $argv[2] : 1;

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $folder;
if (!file_exists($path)) {
    echo '�ļ��в�����';
    die;
}

var_dump(update($version));
die;

function update($version) {
    global $path;
    $file_path = $path . DIRECTORY_SEPARATOR . $version . '.sql';
    $file_o_path = $path . DIRECTORY_SEPARATOR . $version . '_o.sql';

    if (file_exists($file_path)) {
        $sql = file_get_contents($file_location);
        mysql_query($sql);
    }
}

$list = scandir($path); // �õ����ļ��µ������ļ����ļ���
foreach ($list as $file) {//����
    $file_location = $path . DIRECTORY_SEPARATOR . $file; //����·��
    if (!is_dir($file_location) && $file != "." && $file != "..") { //�ж��ǲ����ļ���
        //�ж��Ƿ�ȫ��ִ��
        if ($is_run_all || preg_match('/[0-9]+\.sql/is', $file) != 0) {
            var_dump($file);
            $sql = explode(';', file_get_contents($file_location));
//            if (mysql_query($sql)) {
//                echo 'ִ���ļ� ' . $file . ' �ɹ�';
//            } else {
//                echo 'ִ���ļ� ' . $file . ' ʧ�� ' . mysql_errno();
//            }
        }
    }
}
die;
