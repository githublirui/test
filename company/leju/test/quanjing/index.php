<?php

if (!file_exists(dirname(__FILE__) . '/data/config.php')) {
    header('Location:install/index.php');
    exit();
}else{
    header('Location:admin/index.php');
}
?>

