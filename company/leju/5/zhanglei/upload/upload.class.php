<?php
/**
 * @author by zhanglei
 * @description upload class
 * @time 2013.01.08
 */
class Upload{

    private static $class = null;
    private $uploadFolder = './upload/';
    private $thumbFolder = './thumb/';
    private $markFolder = './mark/';
    private $font = 'C:/Windows/Fonts/simsun.ttc';
    private $type = array('jpeg', 'jpg', 'png', 'gif', 'doc', 'docx', 'xls', 'cvs', 'tar', 'gip', 'txt');
    private $imagetype = array('jpreg', 'jpg', 'gif', 'png');
    private $maxsize = 2097152;
    private $errortype = array(
        0 => "第%u个附件上传成功",
        1 => "第%u个个附件超过服务器大小",
        2 => "第%u个附件超过浏览器限制的大小",
        3 => "第%u个附件仅部分文件被上传",
        4 => "第%u个附件上传至临时文件错误",
        5 => "第%u个附件系统错误",
        6 => "第%u个附件迁移不成功",
        7 => "第%u个附件类型不符合",
        8 => "第%u个附件太大",
        9 => "%s上传控件名称错误"
    );

    // 实例化对象
    private function __construct($uploadFolder = '', $thumbFolder = '', $markFolder = '', $font = ''){   
        if(!empty($uploadFolder)) $this->uploadFolder = $uploadFolder;
        if(!empty($thumbFolder)) $this->thumbFolder = $thumbFolder;
        if(!empty($markFolder)) $this->markFolder = $markFolder;
        if(!empty($font)) $this->font = $font;
        $this->makedir($this->uploadFolder);
        $this->makedir($this->thumbFolder);
        $this->makedir($this->markFolder);
    }

    // 文件上传以及错误的处理
    public function uploadFile($input){
        $suc = array();
        if(empty($_FILES[$input]['name'])){
            return $this->handleFileError('附件', 9);
        }
        if(!is_array($_FILES[$input]['name'])){
            $_FILES[$input]['name'] = array($_FILES[$input]['name']);
            $_FILES[$input]['type'] = array($_FILES[$input]['type']);
            $_FILES[$input]['tmp_name'] = array($_FILES[$input]['tmp_name']);
            $_FILES[$input]['error'] = array($_FILES[$input]['error']);
            $_FILES[$input]['size'] = array($_FILES[$input]['size']);
        }

        foreach($_FILES[$input]['name'] as $key => $name){
            $file_data = pathinfo($name);
            $basename = $file_data['filename'];
            $extension = $file_data['extension'];
            if($_FILES[$input]['size'][$key] > $this->maxsize){
                return $this->handleFileError($key + 1, 8);
            }
            if(!in_array($extension, $this->type)){
                return $this->handleFileError($key + 1, 7);
            }
            $time = time();
            $filename = $time . $basename . '.' . $extension;
            $attachment = $this->getAttachmentPath('upload');
            $path = $attachment . '/' . $filename;
            $_filename = date('Y-m-d') . '/' . $filename;
            if($_FILES[$input]['error'][$key] == 0){
                if(move_uploaded_file($_FILES[$input]['tmp_name'][$key], $path)){
                    $suc[$key + 1] = array_merge($this->handleFileError($key + 1, 0), array('filename' => $_filename), array('path' => $path));
                }else{
                    return $this->handleFileError($key + 1, 6);
                }
            }else{
                return $this->handleFileError($key + 1, $_FILES[$input]['error'][$key]);
            }
        }
        if(count($suc) == 1){
            $suc = $suc[1];
        }
        return !empty($suc) ? $suc : array();
    }
    
    // 处理图片的缩略图
    public function getThumbnail($image, $width, $height){
        try{
            $imageinfo = $this->getImageInfo($image);
            // 打开图片
            $img_resource = $this->getImageCreateResource($image, $imageinfo['extension']);
            $width = $width > $imageinfo['width'] ? $imageinfo['width'] : $width;
            $height = $height > $imageinfo['height'] ? $imageinfo['height'] : $height;
            $image_proportion = $imageinfo['width']/$imageinfo['height'];
            $new_image_proportion = $width/$height;
            if($image_proportion > $new_image_proportion){
                // 原图片的宽高比大, 缩略图的宽 = 传入的width
                $new_width = $width;
                $new_height = $width*$imageinfo['height']/$imageinfo['width'];
            }else{
                // 原图片的宽高比小, 缩略图的高 = 传入的height
                $new_height = $height;
                $new_width = $height*$imageinfo['width']/$imageinfo['height'];
            }
            // 创建新的高宽图片画布
            $new_image_resource = imagecreatetruecolor($new_width, $new_height);
            // 图片填充画布
            imagecopyresampled($new_image_resource, $img_resource, 0, 0, 0, 0, $new_width, $new_height, $imageinfo['width'], $imageinfo['height']);
            $new_image = $imageinfo['filename'] . '_thumb.' . $imageinfo['extension'];
            $attachment = $this->getAttachmentPath('thumb');
            $filename = $attachment . '/' . $new_image;
            if(file_exists($filename)) unlink($filename);
            $_filename = date('Ymd') . $new_image;
            // 输出图片
            $this->outputimage($new_image_resource, $filename, $imageinfo['extension']);
            return $_filename;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    // 处理图片的水印
    public function getWaterMark($image, $text, array $color_arr = array(255, 255, 255)){
        try{
            $color = $this->getColor($color_arr);
            $imageinfo = $this->getImageInfo($image);
            // 打开图片资源
            $image_resource = $this->getImageCreateResource($image, $imageinfo['extension']);
            // 创建画布
            $new_image_resource = imagecreatetruecolor($imageinfo['width'], $imageinfo['height']);
            // 图片填充画布
            imagecopyresampled($new_image_resource, $image_resource, 0, 0, 0, 0, $imageinfo['width'], $imageinfo['height'], $imageinfo['width'], $imageinfo['height']);
            // 设置字体颜色
            $color_resource = imagecolorallocate($new_image_resource, $color[0], $color[1], $color[2]);
            // 计算整个字体的高度以及宽度
            $fontarea = ImageTTFBBox(40, 0, $this->font, $text);
            $x_position = $fontarea[2] - $fontarea[0];
            $y_position = $fontarea[1] - $fontarea[7];
            // 确定填充的字体在图片中间的具体位置(字体的正中央, x轴, y轴)
            $text_x_location = ($imageinfo['width'] - $x_position)/2;
            $text_y_location = ($imageinfo['height'] - $y_position)/2 + $y_position;
            // 填充字体
            imagettftext($new_image_resource, 40, 0, $text_x_location, $text_y_location, $color_resource, $this->font, $text);
            $newimagename = $imageinfo['filename'] . '_mark' . '.' . $imageinfo['extension'];
            $date = date('Ymd');
            $attachment = $this->getAttachmentPath('mark');
            $filename = $attachment . '/' . $newimagename;
            if(file_exists($filename)) unlink($filename);
            $_filename = $date . '/' . $newimagename;
            $this->outputimage($new_image_resource, $filename, $imageinfo['extension']);
            return $_filename;
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }
    
    // 打开图片, 返回资源类型
    private function getImageCreateResource($image, $imagetype){
        switch ($imagetype){
            case 'jpg':
                return imagecreatefromjpeg($image);
                break;
            case 'png':
                return imagecreatefrompng($image);
                break;
            case 'gif':
                return imagecreatefromgif($image);
                break;
            default:
                throw new Exception('创建画布时, 图片类型错误');
                break;
        }
    }
    
    // 输出图片
    private function outputimage($resource, $image, $imagetype){
        switch ($imagetype) {
            case 'jpg':
                return imagejpeg($resource, $image, 100);
                break;
            case 'png':
                return imagepng($resource, $image, 100);
                break;
            case 'gif':
                return imagegif($resource, $image, 100);
                break;
            default:
                throw new Exception('输出图像时, 类型错误');
                break;
        }
    }
    
    // 得到图片信息
    private function getImageInfo($image){
        if(!file_exists($image)){
            throw new Exception('图片不存在');
        }
        $pathinfo = pathinfo($image);
        $extension = $pathinfo['extension'];
        if(!in_array($extension, $this->imagetype)){
            throw new Exception('图片类型错误');
        }
        $arr = getimagesize($image);
        $imageinfo['width'] = $arr[0];
        $imageinfo['height'] = $arr[1];
        $imageinfo['extension'] = $extension;
        $imageinfo['path'] = $image;
        $imageinfo['basename'] = basename($image);
        $imageinfo['filename'] = $pathinfo['filename'];
        return $imageinfo;
    }
    
    // 处理上传产生error
    public function handleFileError($key, $code){
        if(isset($this->errortype[$code])){
            $message = sprintf($this->errortype[$code], $key);
            return array('message' => $message, 'code' => $code);
        }else{
            return array('message' => '未知的错误', 'code' => 100);
        }
    }
    
    private function getAttachmentPath($type = 'upload'){
        $date = date('Ymd');
        switch ($type) {
            case 'upload':
                $path = $this->uploadFolder . $date;
                break;
            case 'thumb':
                $path = $this->thumbFolder . $date;
                break;
            case 'mark':
                $path = $this->markFolder . $date;
                break;
            default:
                break;
        }
        return $this->makedir($path);
    }
    
    // 得到画布填充颜色的rgb值
    private function getColor(array $color){
        if(!is_array($color)){
            throw new Exception('画布填充颜色的类型错误');
        }
        $return = array();
        foreach($color as $_c){
            if(is_numeric($_c)){
                $return[] = $_c;
            }
            if(count($return) >= 3){
                return $return;
            }
        }
        $count = count($return);
        for($i = 0; $i < 3 - $count; $i++){
            array_push($return, 255);
        }
        return $return;
    }

    // 文件夹不存在, 则建立
    private function makedir($folder){
        if(!file_exists($folder)){
            mkdir($folder, 0777, true);
        }
        return $folder;
    }

    // 单例模式
    public static function getInstance($uploadFolder = '', $thumbFolder = '', $markFolder = '', $font = ''){
        if(self::$class === null){
            self::$class = new Upload($uploadFolder = '', $thumbFolder = '', $markFolder = '', $font = '');
        }
        return self::$class;
    }

}