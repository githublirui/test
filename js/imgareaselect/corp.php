<?php

/**
 * Jcrop image cropping plugin for jQuery
 * Example cropping script
 * @copyright 2008-2009 Kelly Hallman
 * More info: http://deepliquid.com/content/Jcrop_Implementation_Theory.html
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/png"))) {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        echo "Stored in: " . "upload/" . $_FILES["file"]["name"];

//        $src = 'upload/' . $_FILES["file"]["name"];
//        if ($_FILES["file"]["type"] == "image/jpeg" || $_FILES["file"]["type"] == "image/pjpeg") {
//            $img_r = imagecreatefromjpeg($src);
//        } else if ($_FILES["file"]["type"] == "image/png") {
//            $img_r = imagecreatefrompng($src);
//        } else if ($_FILES["file"]["type"] == "image/gif") {
//            $img_r = imagecreatefromgif($src);
//        }
//
//        $dst_r = ImageCreateTrueColor($_POST['w'], $_POST['h']);
//
//        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
//
////					header('Content-type: image/jpeg');
//        imagejpeg($dst_r, "upload/get" . $_FILES["file"]["name"]);
//        imagedestroy($dst_r);
//        echo 1231;
//        die;
        $srcFile = "upload/" . $_FILES["file"]["name"];
        $target = "upload/min_" . $_FILES["file"]["name"];
        $data = GetImageSize($srcFile);
        switch ($data[2]) {
            case 1:   //gif
                if (function_exists('ImageCreateFromGIF'))
                    $_im = ImageCreateFromGIF($srcFile);
                break;
            case 2:   //jpg
                if (function_exists('imagecreatefromjpeg'))
                    $_im = imagecreatefromjpeg($srcFile);
                break;
            case 3:   //png
                if (function_exists('ImageCreateFromPNG'))
                    $_im = ImageCreateFromPNG($srcFile);
                break;
        }
        $width = $_POST['w'];
        $height = $_POST['h'];
        $newimg = imagecreatetruecolor($_POST['w'], $_POST['h']);
        imagecopyresampled($newimg, $_im, 0, 0, $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
        $_result = ImageJpeg($newimg, $target, 100);
        ImageDestroy($_im);
        ImageDestroy($newimg);
        unlink($srcFile);
        
        echo 'ok';
    } else {
        echo "Invalid file";
    }


    exit;
}

function imgpro($srcFile) {
    $data = GetImageSize($srcFile);
    switch ($data[2]) {
        case 1:   //gif
            if (function_exists('ImageCreateFromGIF'))
                $_im = ImageCreateFromGIF($srcFile);
            break;
        case 2:   //jpg
            if (function_exists('imagecreatefromjpeg'))
                $_im = imagecreatefromjpeg($srcFile);
            break;
        case 3:   //png
            if (function_exists('ImageCreateFromPNG'))
                $_im = ImageCreateFromPNG($srcFile);
            break;
    }
    $width = $_POST['w'];
    $height = $_POST['h'];
    $newimg = imagecreatetruecolor($_POST['w'], $_POST['h']);
    imagecopyresampled($newimg, $_im, 0, 0, $_POST['x'], $_POST['y'], $_POST['w'], $_POST['h'], $_POST['w'], $_POST['h']);
    $_result = ImageJpeg($newimg, $target, 100);

    ImageDestroy($_im);
    ImageDestroy($newimg);
}
