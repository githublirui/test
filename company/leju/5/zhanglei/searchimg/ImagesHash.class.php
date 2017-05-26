<?php
class ImagesHash{

	public $rate = 2;
    public static $_class = null;
    
    private function __construct(){
        
    }
    
    public static function getInstance(){
        if(self::$_class === null){
            self::$_class = new ImagesHash();
        }
        return self::$_class;
    }

    /**
     * @param $file 图片真实路径
     * @brief 得到图片的hash值
     * @return hash值
     */
	public function getImagesHash($file){
		if(!function_exists('imagecreatetruecolor')){
			throw new Exception('must load gd lib', 1);
		}
		$isString = false;
		if(is_string($file)){
			$file = array($file);
			$isString = true;
		}
		$result = array();
		foreach($file as $f){
			$result[] = $this->hash($f);
		}
		return $isString ? $result[0] : $result;
	}

    /**
     * @param type $imgHash 第一张图片的hash值
     * @param type $otherImgHash 第二张图片的hash值
     * @brief 比较两张图片hash的非相似的次数, 如果次数小于 5 * $this->rate * $this->rate 图片相似, 否则不相似
     * @return boolean
     */
	public function checkIsSimilarImg($imgHash, $otherImgHash){
		if(strlen($imgHash) !== strlen($otherImgHash)){
            return false;
        }
		$count = 0;
		$len = strlen($imgHash);
		for($i = 0; $i < $len; $i++){
			if($imgHash[$i] !== $otherImgHash[$i]){
				$count++;
			}
		}
		return $count <= (5 * $this->rate * $this->rate) ? true : false;
	}

    /**
     * @param $file 原图片真实路径
     * @brief
     *     将原图片缩小成 8 * $this->rate 正方形大小
     *     并且得到缩小后图片的hash算法值
     * @return boolean
     */
	public function hash($file){
		if(!file_exists($file)){
			return false;
		}
		$height = 8 * $this->rate;
		$width = 8 * $this->rate;
		$img = imagecreatetruecolor($width, $height);
		list($w, $h) = getimagesize($file);
		$source = $this->createImg($file);
		imagecopyresampled($img, $source, 0, 0, 0, 0, $width, $height, $w, $h);
		$value = $this->getHashValue($img);
		imagedestroy($img);
		return $value;
	}

    /**
     * @param   $img    图片路径
     * @brief   
     *     数组下Y轴的点的坐标X下X轴点的坐标值是图片中这个点绿色的RGB的值
     *     并且取出图片中每个点的RGB的平均值
     *     然后计算每个点绿色的RGB的值与平均值比大小, 大于平均值1 小于平均值0
     *     最后返回这个图片的string eg: 1010110000110
     * @return  图片的hash值
     */
	public function getHashValue($img){
		$width = imagesx($img);
		$height = imagesy($img);
		$total = 0;
		$array = array();
		for($y = 0; $y < $height; $y++){
			for($x = 0; $x < $width; $x++){
				$gray = (imagecolorat($img, $x, $y) >> 8) & 0xFF;
				if(isset($array[$y]) && !is_array($array[$y])){
					$array[$y] = array();
				}
				$array[$y][$x] = $gray;
				$total += $gray;
			}
		}
		$average = intval($total / (64 * $this->rate * $this->rate));
		$result = '';
		for($y = 0; $y < $height; $y++){
			for($x = 0; $x < $width; $x++){
				if($array[$y][$x] >= $average){
					$result .= 1;
				}else{
					$result .= 0;
				}
			}
		}
		return $result;
	}

    /**
     * @param   $file   图片真实路径
     * @brief   创建新的图片
     * @return  图片资源
     */
	public function createImg($file){
		$ext = $this->getFileExt($file);
		if($ext === 'jpeg'){
			$ext = 'jpg';
		}
		$img = null;
		switch ($ext){
			case 'png': 
				$img = imagecreatefrompng($file);
			break;
			case 'jpg': 
				$img = imagecreatefromjpeg($file);
			break;
			case 'gif': 
				$img = imagecreatefromgif($file);
			break;
		}
		return $img;
	}

    /**
     * @param   $file   文件真实路径
     * @return  返回文件扩展名
     */
	public function getFileExt($file){
		$infos = explode('.', $file);
		$ext = strtolower($infos[count($infos) - 1]);
		return $ext;
	}

}
