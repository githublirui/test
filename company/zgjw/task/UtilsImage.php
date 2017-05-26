<?php

/** 缩略图
  //裁切图片函数
 * $srcFile - 图形文件
 * $pixel - 尺寸大小，如：400*300
 * $_quality - 图片质量，默认100
 * $cut - 是否裁剪，默认1，当$cut=0的时候，将不进行裁剪
 * $cache - 如果有缓存，是否直接用缓存，默认true
 * 示例："< img src=\"".MiniImg('images/image.jpg','300*180',100,1)."\">"
 * MiniImg("1.jpg","100*200");
 */
class UtilsMiniImg {

    public static function MiniImg($srcFile, $pixel, $target_file, $_quality = 100, $cut = 1, $cache = false) {
        if ($srcFile == ".")
            return;

        $pixelInfo = explode('*', $pixel);
        $pathInfo = pathinfo($srcFile);
        $_cut = intval($cut);
        $searchFileName = preg_replace("/\.([A-Za-z0-9]+)$/isU", "_" . $pixelInfo[0] . "x" . $pixelInfo[1] . "_" . $cut . ".\\1", $pathInfo['basename']);
        //$miniFile = $pathInfo['dirname'].'/'.$searchFileName;
        $ShowTime4 = date("Y-m-d H:i:s");
        $Pic_FileName = strtotime($ShowTime4) . rand(1, 10);

        $miniFile = $target_file;  //改成替换原图

        if ($cache and file_exists($miniFile))
            return $miniFile;

        $data = GetImageSize($srcFile);
        $FuncExists = 1;
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
//            case 6:   //bmp，这里需要用到ImageCreateFromBMP
//                $_im = ImageCreateFromBMP($srcFile);
//                break;
        }
        if (!$_im)
            return $srcFile;
        $sizeInfo['width'] = @imagesx($_im);
        $sizeInfo['height'] = @imagesy($_im);
        if (!$sizeInfo['width'] or ! $sizeInfo['height'])
            return $srcFile;
        if ($sizeInfo['width'] == $pixelInfo[0] && $sizeInfo['height'] == $pixelInfo[1]) {
            return $srcFile;
        } elseif ($sizeInfo['width'] < $pixelInfo[0] && $sizeInfo['height'] < $pixelInfo[1] && $miniMode == '2') {
            return $srcFile;
        } else {
            $resize_ratio = ($pixelInfo[0]) / ($pixelInfo[1]);
            $ratio = ($sizeInfo['width']) / ($sizeInfo['height']);
            if ($cut == 1) {
                $newimg = imagecreatetruecolor($pixelInfo[0], $pixelInfo[1]);
                if ($ratio >= $resize_ratio) {          //高度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], (($sizeInfo['height']) * $resize_ratio), $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {               //宽度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], $sizeInfo['width'], (($sizeInfo['width']) / $resize_ratio));
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            } else {                //不裁图
                if ($ratio >= $resize_ratio) {
                    $newimg = imagecreatetruecolor($pixelInfo[0], ($pixelInfo[0]) / $ratio);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], ($pixelInfo[0]) / $ratio, $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {
                    $newimg = imagecreatetruecolor(($pixelInfo[1]) * $ratio, $pixelInfo[1]);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, ($pixelInfo[1]) * $ratio, $pixelInfo[1], $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            }


            ImageDestroy($_im);
            ImageDestroy($newimg);

            if ($_result)
                return $miniFile;
            return $srcFile;
        }
    }

}
