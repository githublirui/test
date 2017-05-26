<?php
/**
 * @brief 图片处理
 * move by zhengyifeng 2011.6.1
 * @todo 移到图片类
 */

if( !extension_loaded('gd') ){ die('php-gd2 should be install ');}

/*
GD库中没有对BMP处理的函数,该 函数并没有处理2色位的图!
*/
if (!function_exists('imagecreatefrombmp'))
{
    function imagecreatefrombmp($fname)
    {
        $buf=@file_get_contents($fname);
        if(strlen($buf)<54)   return   false;
        $file_header=unpack("sbfType/LbfSize/sbfReserved1/sbfReserved2/LbfOffBits",substr($buf,0,14));
        if($file_header["bfType"]!=19778)   return   false;
        $info_header=unpack("LbiSize/lbiWidth/lbiHeight/sbiPlanes/sbiBitCountLbiCompression/LbiSizeImage/lbiXPelsPerMeter/lbiYPelsPerMeter/LbiClrUsed/LbiClrImportant",substr($buf,14,40));

        if($info_header["biBitCountLbiCompression"]==2)   return   false;

        $line_len=round($info_header["biWidth"]*$info_header["biBitCountLbiCompression"]/8);
        $x=$line_len%4;
        if($x>0)   $line_len+=4-$x;

        $img=imagecreatetruecolor($info_header["biWidth"],$info_header["biHeight"]);
        switch($info_header["biBitCountLbiCompression"]){
            case   4:
                $colorset=unpack("L*",substr($buf,54,64));
                for($y=0;$y<$info_header["biHeight"];$y++){
                    $colors=array();
                    $y_pos=$y*$line_len+$file_header["bfOffBits"];
                    for($x=0;$x<$info_header["biWidth"];$x++){
                        if($x%2)
                        $colors[]=$colorset[(ord($buf[$y_pos+($x+1)/2])&0xf)+1];
                        else
                        $colors[]=$colorset[((ord($buf[$y_pos+$x/2+1])>>4)&0xf)+1];
                    }
                    imagesetstyle($img,$colors);
                    imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
                }
                break;
            case   8:
                $colorset=unpack("L*",substr($buf,54,1024));
                for($y=0;$y<$info_header["biHeight"];$y++){
                    $colors=array();
                    $y_pos=$y*$line_len+$file_header["bfOffBits"];
                    for($x=0;$x<$info_header["biWidth"];$x++){
                        $colors[]=$colorset[ord($buf[$y_pos+$x])+1];
                    }
                    imagesetstyle($img,$colors);
                    imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
                }
                break;
            case   16:
                for($y=0;$y<$info_header["biHeight"];$y++){
                    $colors=array();
                    $y_pos=$y*$line_len+$file_header["bfOffBits"];
                    for($x=0;$x<$info_header["biWidth"];$x++){
                        $i=$x*2;
                        $color=ord($buf[$y_pos+$i])|(ord($buf[$y_pos+$i+1])<<8);
                        $colors[]=imagecolorallocate($img,(($color>>10)&0x1f)*0xff/0x1f,(($color>>5)&0x1f)*0xff/0x1f,($color&0x1f)*0xff/0x1f);
                    }
                    imagesetstyle($img,$colors);
                    imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
                }
                break;
            case   24:
                for($y=0;$y<$info_header["biHeight"];$y++){
                    $colors=array();
                    $y_pos=$y*$line_len+$file_header["bfOffBits"];
                    for($x=0;$x<$info_header["biWidth"];$x++){
                        $i=$x*3;
                        $colors[]=imagecolorallocate($img,ord($buf[$y_pos+$i+2]),ord($buf[$y_pos+$i+1]),ord($buf[$y_pos+$i]));
                    }
                    imagesetstyle($img,$colors);
                    imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
                }
                break;
            default:
                return   false;
                break;
        }
        return   $img;
    }
}
class ImageNamespace
{
    /**
	 * 创建缩略图
	 * $crop: 在图片比例与期望不符时，按要求比例进行裁剪并缩放到指定大小；否则，保持原图片比例，不超过指定的宽高。
	 */
    public static function resize( $src, $dest, $w, $h, $crop=false)
    {
        if( file_exists($src)  && isset($dest) )
        {
            $src_size   = getimagesize($src);
            $src_extension = $src_size[2];
            $src_w = $src_size[0];
            $src_h = $src_size[1];
            if( $crop )
            {
                $ratio = min($src_w/$w, $src_h/$h);
                $src_w = $w * $ratio;
                $src_h = $h * $ratio;
            }
            else
            {
                $ratio = min($w/$src_w, $h/$src_h);
                $w = $src_w*$ratio;
                $h = $src_h*$ratio;
            }
            //			die("$ratio,$src_w,$src_h,$w,$h");
        }
        //建立图像
        $dest_image = imagecreatetruecolor($w,$h);
        //根据文件格式读取图片
        switch( $src_extension )
        {
            case 1:
                $srcImage = imagecreatefromgif($src);
                break;
            case 2:
                $srcImage = imagecreatefromjpeg($src);
                break;
            case 3:
                $srcImage = imagecreatefrompng($src);
                break;
            case 6:
                $srcImage = imagecreatefrombmp($src);
                break;
        }
        //取样压缩图
        imagecopyresampled($dest_image, $srcImage, 0, 0, 0, 0,$w,$h,$src_w,$src_h);
        switch ( $src_extension )
        {
            case 1:
                imagegif($dest_image,$dest);
                break;
            case 2:
            case 6:
                //bmp的图片强行保存为jpg
                imagejpeg($dest_image,$dest,100);
                break;
            case 3:
                imagepng($dest_image,$dest);
                break;
                //释放资源
                imagedestroy($dest_image);
        }
        return true;
    }

    /**
     * 检测上传的图片资源并上传本地服务器
     * @param <type> $fileInfo
     * @param <type> $dstFile
     * @param <type> $isAvtar
     * @return <type>
     */
    public static function ImageCheck($fileInfo,$dstFile,$isAvtar=1){
        $errorMsg = '';
        if ($fileInfo['error'] != 0){
           $errorMsg = $fileInfo['error'];
        }
        elseif (!is_uploaded_file($fileInfo['tmp_name'])){
            $errorMsg =  'Error File';
        }
        else if(!move_uploaded_file($fileInfo['tmp_name'],$dstFile)){
            $errorMsg = 'Upload tmp file to local faild';
        }

        $allowtype = array("jpg","jpeg","png");
        $imgType=self::pictype($dstFile);
        if (!in_array($imgType,$allowtype)){
            $errorMsg = '图片格式错误，请重试。只支持文件格式：jpg和png。';
        }

        if (intval($fileInfo['size']) >=5242880) // 图片不能大于5M
        {
            if ($isAvtar)
            {
                self::ResizeImage($dstFile,$imgType,480,600,$dstFile);
            }
            else{
                $errorMsg = '图片文件过大，请重试。::文件大小超出限制。';
            }
        }

        return $errorMsg;
    }

    /**
     * 检测上传的图片资源并上传本地服务器
     * @param <array> $fileInfo
     * @param <string> $dstFile
     * @param <int> $i
     * @return <type>
     */
    public static function MultiImageCheck($fileInfo,$dstFile,$i=0){
        if ($fileInfo['error'][$i] != 0){
            $errorMsg = $fileInfo['error'][$i];
        }
        else if (!is_uploaded_file($fileInfo['tmp_name'][$i])){
            $errorMsg = 'Error File';
        }
        else if (!move_uploaded_file($fileInfo['tmp_name'][$i],$dstFile)){
            $errorMsg =  'Upload tmp file to local faild';
        }

        $allowtype = array("jpg","jpeg","png");
        $imgType = self::pictype($dstFile);
        if (!in_array($imgType,$allowtype)){
            $errorMsg =  '图片格式错误，请重试。只支持文件格式：jpg和png。';
        }

        if (intval($fileInfo['size'][$i]) >=5242880){
            $errorMsg =  '图片文件过大，请重试。::文件大小超出限制。';
        }
        return $errorMsg;
    }
    /**
     * 获得图片类别
     * @param <string> $file
     * @return <string>
     */
    public static function pictype ( $file )
    {
         /*$png_header = "/x89/x50/x4e/x47/x0d/x0a/x1a/x0a";
         $jpg_header = "/xff/xd8";*/
         $type = 'unknow';
         $header = file_get_contents ( $file , 0 , NULL , 0 , 5 );
         //echo bin2hex($header);
         if ( $header { 0 }. $header { 1 }== "\x89\x50" )
         {
             $type = 'png';
         }
         else if( $header { 0 }. $header { 1 } == "\xff\xd8" )
         {
             $type ='jpeg';
         }
         else if( $header { 0 }. $header { 1 }. $header { 2 } == "\x47\x49\x46" )
         {
             $type = 'gif';
         }
         return $type;
    }
    /**
     *
     * @param <type> $srcImg
     * @param <type> $imgType
     * @param <type> $maxwidth
     * @param <type> $maxheight
     * @param <type> $name 
     */
    public static function ResizeImage($srcImg,$imgType,$maxwidth,$maxheight,$name)
    {
        $im = null;
        switch($imgType)
        {
        case 'png':
            $im=imagecreatefrompng($srcImg);
            break;
        case 'gif':
            $im=imagecreatefromgif($srcImg);
            break;
        case 'jpeg':
        default:
            $im=imagecreatefromjpeg($srcImg);
            break;
        }

        //取得当前图片大小
        $width = imagesx($im);
        $height = imagesy($im);
        //生成缩略图的大小
        if(($width > $maxwidth) || ($height > $maxheight)){
            $widthratio = $maxwidth/$width;
            $heightratio = $maxheight/$height;
            if($widthratio < $heightratio){
                $ratio = $widthratio;
            }else{
                $ratio = $heightratio;
            }
            $newwidth = $width * $ratio;
            $newheight = $height * $ratio;

            if(function_exists("imagecopyresampled")){
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }else{
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            }
            ImageJpeg ($newim,$name);
            ImageDestroy ($newim);
        }else{
            ImageJpeg ($im,$name);
        }
    }
}
