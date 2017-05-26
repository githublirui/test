<?php
/*
 * 项目: 通过数据生成图片
 * 版本: 1.0
 * 文件名: WapImageStat.class.php
 * 整理者: zhanglei1
 * 编写日期: 2014/03/26
 * 简要描述: 常用统计图片显示结果类集，为综合几个类库从新编写
*/
class WapImageStat{

	//定义图象宽度
	private $img_width  = 150;

	//定义图象高度
	private $img_height = 75;
	
	//定义被统计数据数组
	private $statistic  = array();    
	
	//调查选项
	private $items      = array();    

	private $left       = 70;		//图表左距
	private $right      = 30;		//图表右距
	private $top        = 30;		//图表上距
	private $down       = 30;		//图表下距

	private $pieCenterX = 160;		//圆心x坐标
	private $pieCenterY = 85;		//圆心y坐标

	private $pieRadius = 300;		//圆半径
	private $pieHeight = 150;		//圆高度

	//图象前景色
	private $color = array();

	//图象背景色
	private $bkcolor = array();
    
    private $x_color = array();     // X轴字体颜色
    private $y_color = array();     // Y轴字体颜色
    private $d_color = array();     // 数据数字字体颜色
    private $x_space;               // X轴第一个点距离原点的距离
    
    /**
     * 函数名称 set_x_color
     * @brief 设置X轴字体颜色
     */
    public function set_x_color($color){
        if(empty($color) || !is_array($color) || count($color) != 3){
            $this->printMessage("请传入X轴颜色字体");
        }else{
            $this->x_color = $color;
        }
    }
    
    /**
     * @brief 设置Y轴字体颜色
     */
    public function set_y_color($color){
        if(empty($color) || !is_array($color) || count($color) != 3){
            $this->printMessage("请传入Y轴字体颜色");
        }else{
            $this->y_color = $color;
        }
    }
    
    /**
     * @brief 设置数据字体颜色
     */
    public function set_d_color($color){
        if(empty($color) || !is_array($color) || count($color) != 3){
            $this->printMessage("请传入数据字体颜色");
        }else{
            $this->d_color = $color;
        }
    }
    
    /**
     * 
     */
    public function set_x_space($space = 0){
        $this->x_space = intval($space);
    }

	/*
	 * 函数名称:set_data($data)
	 * 简要描述:将统计数据附给类属性$this->statistic以备使用
	 * 输入:string (接受格式"9,5,4,8")
	 * 输出:void
	 */
	public function set_data($data){
		if(empty($data)){
			$this->printMessage("请填写统计数据");
		}else{
			$this->statistic = explode(",", $data);
		}
	}

	/*
	 * 函数名称:printMessage($data)
	 * 简要描述:输出信息
	 * 输入:string()
	 * 输出:messge
	 */
	public function printMessage($message){
		throw new Exception($message);
	}

	/*
	 * 函数名称:set_size($width,$height)
	 * 简要描述:设置图象宽高，也就是统计图表的长宽
	 * 输入:mixed (图象宽，高)
	 */
	public function set_size($width, $height){
		$this->img_width = $width;
		$this->img_height = $height;

		$this->pieCenterX = $width + 10;  //圆心x坐标
		$this->pieCenterY = $height + 10;   //圆心y坐标

		$this->pieRadius = $width * 2;      //圆半径
		$this->pieHeight = $height * 2;      //圆高度
	}

	/*
	 * 函数名称:set_border($left,$right,$top,$down)
	 * 简要描述:设置统计图和图象边框的距离
	 * 输入:mixed (左，右，上，下);
	 */
	public function set_border($left, $right, $top, $down){
		$this->left = $left;
		$this->right = $right;
		$this->top = $top;
		$this->down = $down;
	}

	/*
	 * 函数名称:set_items($items)
	 * 简要描述:将统计条目写入数组以备使用,横轴
	 * 输入:mixed ("1,2,3")
	 * 输出:void
	 */
	public function set_items($items){
		if(empty($items)){
			$this->printMessage("请填写统计条目");
		}else{
			$this->items = explode(",", $items);
		}
	}

	/*
	 * 函数名称:set_color($color)
 	 * 简要描述:设置前景颜色
	 * 输入:array (每个条目的颜色，为三原色)；
	 * 输出:void
	 */
	public function set_color($color){
		if(!is_array($color) || count($color) !== 3){
			$this->printMessage("统计颜色太少");
		}
		$this->bkcolor = $color;
        
        if(empty($this->x_color)){
            $this->x_color = $this->bkcolor;
        }
        if(empty($this->y_color)){ 
            $this->y_color = $this->bkcolor;
        }
        if(empty($this->d_color)){
            $this->d_color = $this->bkcolor;
        }
	}

	/*
	 * 函数名称:set_bkcolor($color)
	 * 简要描述:设置背景颜色
	 * 输入:array (每个条目的颜色，为三原色)；如array(0xEE, 0xEE, 0xEE);
	 */
	public function set_bkcolor($color){
		if(!is_array($color) || count($color) !== 3){
			$this->printMessage("格式错误");
		}
		$this->bkcolor = $color;
	}

	/*
	 * 函数名称:stat_pie()
	 * 简要描述:饼型显示统计数据
	 * 输入:void
	 * 输出:直接输出到浏览器
	 */
	public function stat_pie(){
		$PieCenterX = $this->pieCenterX;
		$PieCenterY = $this->pieCenterY;
		$DoubleA    = $this->pieRadius;
		$DoubleB    = $this->pieHeight;

		//统计数据的总和
		$DataTotal = 0;
		for($i=0; $i< count($this->statistic); $i++){
			$DataTotal += $this->statistic[$i];
		}

		//初始化方形图片
		$image = imagecreate($this->img_width * 3 + 50 ,$this->img_height * 3);

		$backColor = imagecolorallocate($image, 233, 246, 253);          //背景色
		imagefill($image, 0, 0, $backColor);//填充背景
		$colorBorder = imagecolorallocate($image,0, 0, 0);

		//画扇形
		$Degrees = 0;
		for($i = 0; $i < count($this->statistic); $i++){
			$StartDegrees = round($Degrees);
			$Degrees += ($this->statistic[$i]/$DataTotal) * 360;

			$EndDegrees = round($Degrees);
			$percent = number_format($this->statistic[$i]/$DataTotal * 100, 1);

			//画弧
			$color_bit    = $this->color[$i];
			$currentColor = imagecolorallocate($image, $color_bit[0],$color_bit[1], $color_bit[2]);
			imagearc($image, $PieCenterX, $PieCenterY, $DoubleA, $DoubleB, $StartDegrees, $EndDegrees, $currentColor);

			//画直线
			list($ArcX, $ArcY) = $this->pie_point($StartDegrees, $this->img_width, $this->img_height);
			imageline($image, $PieCenterX, $PieCenterY, floor($PieCenterX + $ArcX) ,floor($PieCenterY + $ArcY), $currentColor);

			//画直线
			list($ArcX, $ArcY) = $this->pie_point($EndDegrees,$this->img_width, $this->img_height);
			imageline($image, $PieCenterX, $PieCenterY, ceil($PieCenterX + $ArcX), ceil($PieCenterY + $ArcY), $currentColor);

			//填充圆弧
			$MidPoint = round((($EndDegrees - $StartDegrees)/2) + $StartDegrees);
			list($ArcX, $ArcY) = $this->Pie_point($MidPoint, $this->img_width*3/4 , $this->img_height*3/4);
			imagefilltoborder($image ,floor($PieCenterX + $ArcX), floor($PieCenterY + $ArcY), $currentColor, $currentColor);


			//标注百分比
			imagestring($image, 1, floor($PieCenterX + $ArcX-5), floor($PieCenterY + $ArcY-5), $percent."%", $colorBorder);
			if ($StartDegrees>=0 && $StartDegrees<=180){
				if($EndDegrees <= 180){
					for($k = 1; $k < 15; $k++){
						imagearc($image, $PieCenterX, $PieCenterY + $k, $DoubleA, $DoubleB, $StartDegrees, $EndDegrees, $currentColor);
					}
				}else{
					for($k = 1; $k < 15; $k++){
						imagearc($image, $PieCenterX, $PieCenterY + $k, $DoubleA, $DoubleB, $StartDegrees, 180, $currentColor);
					}
				}
			}
		}

		//输出图象
		header('Content-type: image/jpeg');
		imagejpeg($image, '', 100);
		imagedestroy($image);
	}

	/*
	 * 函数名称:stat_line()
	 * 简要描述:折线图显示结果
	 * 输入:void
	 * 输出:直接输出到浏览器
	 */
	public function stat_line($image_path){
		$left = $this->left;
		$right = $this->right;
		$top = $this->top;
		$down = $this->down;
		$data = $this->statistic;

		$max_value = 1;
        $min_value = min($data);
		$p_x = array();
		$p_y = array();

		for($i = 0; $i < count($data); $i++){
			if(!is_numeric($data[$i])){
				$this->printMessage('数据格式不正确');
			}
			if($data[$i] > $max_value){
                $max_value = $data[$i];
            }
		}

		// X轴之间每个item之间的空格
		$space = round(($this->img_width - $left - $right)/count($data));
		
		// 创建画布
		$image = imagecreate($this->img_width, $this->img_height);
        imageantialias($image, true);
		
		$bkcolor = $this->bkcolor;

		//背景色
		$white = imagecolorallocate($image, $bkcolor[0], $bkcolor[1], $bkcolor[2]); 

		//横竖X, Y坐标颜色
		$x_color = imagecolorallocate($image, $this->x_color[0], $this->x_color[1], $this->x_color[2]);
        $y_color = imagecolorallocate($image, $this->y_color[0], $this->y_color[1], $this->y_color[2]);

		//折线的颜色
		$line_color = imagecolorallocate($image, $this->d_color[0], $this->d_color[1], $this->d_color[2]);
		imagesetthickness($image, 2);

		// X Y 轴留出画布长宽减去最大值得1/2出来
		imageline($image, $left, $this->img_height - $down, $this->img_width - $right/2, $this->img_height - $down, $x_color);
		imageline($image, $left, $top/2,  $left, $this->img_height - $down, $y_color);
        
        // 刻度, 每个data相对应多少个img_height
        $graduation  = $this->img_height / $max_value;
        //echo $graduation;die;
        
        // 由于数据太接近, 则扩大刻度, 前提是, 最小值扩大之后应该小于$this->img_height - $top - $down
        $multiple = ($this->img_height - $top - $down) / ($graduation * $min_value);
        if($multiple < 3){
            $multiple = 3;
        }else{
            $multiple = $multiple / 2;
        }
        // 将X Y轴的坐标放入数组内, 并且X轴每个坐标向右移动$this->x_space
		for($i=0; $i<count($data); $i++){
			array_push($p_x, $this->x_space + $left + round($i * $space));
            // 图像最高点不要太高, 默认加上图片高度的2/5倍即可, 并且将刻度扩大3倍
			array_push($p_y, round($this->img_height * 2 / 5) + $top + round(($this->img_height - $top - $down) * $multiple * (1 - $data[$i]/$max_value)));
		}

        /* 画出Y轴上刻度线
		imageline($image, $left, $top,  $left + 6, $top, $y_color);
		imagestring($image, 2, $left/6, $top - 6, number_format($max_value, 2), $y_color);

		imageline($image, $left, $top + ($this->img_height - $top - $down) * 1 / 4, $left + 6, $top + ($this->img_height - $top-$down) * 1 / 4, $y_color);
		imagestring($image, 2, $left/6, $top + ($this->img_height - $top - $down) * 1 / 4 - 6, number_format($max_value * 3 / 4, 2), $y_color);

		imageline($image, $left, $top + ($this->img_height - $top - $down) * 2 / 4, $left + 6, $top + ($this->img_height - $top - $down) * 2 / 4, $y_color);
		imagestring($image, 2, $left/6, $top + ($this->img_height - $top - $down) * 2 / 4 - 6, number_format($max_value * 2 / 4, 2), $y_color);

		imageline($image, $left, $top + ($this->img_height - $top - $down) * 3 / 4,  $left + 6, $top + ($this->img_height - $top - $down) * 3 / 4, $y_color);
		imagestring($image, 2, $left/6, $top + ($this->img_height - $top - $down) * 3 / 4 - 6, number_format($max_value * 1 / 4, 2), $y_color);
        */
        
		$d_color = imagecolorallocate($image, $this->d_color[0], $this->d_color[1], $this->d_color[2]);
		for($i = 0; $i < count($data); $i++){
			imageline($image, $this->x_space + $left + $i * $space, $this->img_height - $down,  $this->x_space + $left + $i * $space, $this->img_height - $down + 6, $x_color);
			ImageTTFText($image, 12, 0, $this->x_space + $left + $i * $space - 10, $top + ($this->img_height - $top - $down) + 20, $x_color, CODE_BASE2 . '/util/image/songti.ttf', $this->items[$i]);
		}

		// 连接每个数据的直线, 并且在每个数据点划上一个小矩形
		for($i = 0; $i < count($data); $i++){
			if($i + 1 != count($data)){
				imageline($image, $p_x[$i], $p_y[$i],  $p_x[$i + 1], $p_y[$i + 1], $line_color);
				//$point_color = imagecolorallocate($image, $this->color[$i][0], $this->color[$i][1], $this->color[$i][2]);
				$point_color = imagecolorallocate($image, $d_color[0], $d_color[1], $d_color[2]);
				imagefilledrectangle($image, $p_x[$i] - 1, $p_y[$i] - 1, $p_x[$i] + 1, $p_y[$i] + 1, $point_color);
			}
		}
		
		// 给最后一个数据点划上小矩形
		imagefilledrectangle($image, $p_x[count($data) - 1] - 1, $p_y[count($data) - 1] - 1,  $p_x[count($data) - 1] + 1, $p_y[count($data) - 1] + 1, $line_color);

		// 给每个数据点标上数字
		for($i = 0; $i < count($data); $i++){
			imagestring($image, 3, $p_x[$i] + 4, $p_y[$i] - 12, $data[$i], $d_color);
		}
        
        // 给图片画阴影区
        //print_r($p_y);die;
        imagesetthickness($image, 1);
        $dashes_space = 5;
        $dashes = ceil($space/$dashes_space);
        if(!empty($p_y)){
            $first_height = $p_y[0];
            $key = 0;
            foreach($p_y as $key => $value){
                if(($key + 1) != count($p_y)){
                    $tan = abs($p_y[$key + 1] - $p_y[$key]) / $space;
                    $i = -1;
                    while($i < $dashes){
                        $y_coordinate = ($p_y[$key + 1] >= $p_y[$key]) ? ($p_y[$key] + $tan * $dashes_space * ($i + 1) + 10) : ($p_y[$key] - $tan * $dashes_space * ($i + 1) + 10);
                        $diff = $tan * $dashes_space * ($i + 1);
                        //$d_y_coordinate = $diff + $first_height + 20 + $down > $this->img_height ? $this->img_height - $down - 10 : $diff + $first_height + 10;
                        $d_y_coordinate = $diff + $first_height + 10;
                        imageline($image, $p_x[$key] + ($i + 1) * $dashes_space, $y_coordinate, $left, $d_y_coordinate, $d_color);
                        $i = $i + 1;
                    }
                }
            }
        }
		header('Content-type: image/jpeg; charset=utf-8');
		imagejpeg($image, $image_path, 100);
		imagedestroy($image);
	}

	/*
	 * 函数名称:stat_bar()
	 * 简要描述:柱型图显示结果
	 * 输入:void
	 * 输出:直接输出到浏览器
	 */
	public function stat_bar(){

		$left = $this->left;
		$right = $this->right;
		$top = $this->top;
		$down = $this->down;

		$data = $this->statistic;
		$space = ($this->img_width - $left - $right)/(count($data) * 3);
		$bar_width = $space * 2;

		$max_value = 1;
		for($i = 0; $i < count($data); $i++){
			if(!is_numeric($data[$i])){
				$this->printMessage('请填写正确的格式');
			}
			if($data[$i] > $max_value) $max_value = $data[$i];
		}

		$bar_height = array();
		$image = imagecreate($this->img_width,$this->img_height);
		$bkcolor = $this->bkcolor;
		//背景色
		$white = imagecolorallocate($image, $bkcolor[0], $bkcolor[1], $bkcolor[2]);
		//坐标轴的颜色
		$img_color = imagecolorallocate($image, 0x00, 0x00, 0x00);
		
		//X轴
		imageline($image, $left, $this->img_height - $down, $this->img_width - $right / 2, $this->img_height - $down, $img_color); 
		//y轴
		imageline ($image, $left, $top/2, $left, $this->img_height - $down, $img_color);
		//y轴最上的分割线
		imageline ($image, $left, $top, $left + 6, $top, $img_color); 
		//画出y轴最大值
		imagestring($image, 3, $left/4, $top, round($max_value), $img_color);

		//y轴上第二个分割线
		//从上向下画
		imageline($image, $left, $top + ($this->img_height - $top - $down) * 1/4, $left + 6, round($top + ($this->img_height - $top - $down) * 1/4), $img_color);
		//画出y轴上第二个数字round($max_value*3/4)
		imagestring($image, 3, $left/4, $top + ($this->img_height - $top - $down) * 1/4, round($max_value * 3/4), $img_color);
		//y轴上第三个分割线
		imageline($image, $left, $top + ($this->img_height - $top - $down) * 2/4, $left + 6, $top + ($this->img_height - $top - $down) * 2/4, $img_color);
		//y轴上的第三个数字round($max_value*2/4)
		imagestring($image, 3, $left/4, $top + ($this->img_height - $top - $down) * 2/4, round($max_value * 2/4), $img_color);
		//y轴上的第四个分割线
		imageline($image, $left, $top + ($this->img_height - $top - $down) * 3/4, $left + 6, $top + ($this->img_height - $top - $down) * 3/4, $img_color);
		//y轴上的第四个数字round($max_value*3/4)
		imagestring($image, 3, $left/4, $top + ($this->img_height - $top - $down) * 3/4, round($max_value * 1/4), $img_color);

		//生成bar_height数组
		for($i = 0; $i < count($data); $i++){
			array_push($bar_height, round(($this->img_height - $top - $down) * $data[$i] / $max_value));
		}

		for($i = 0; $i<count($data); $i++){
			$bar_color = imagecolorallocate($image, $this->color[$i][0], $this->color[$i][1], $this->color[$i][2]);
			imagefilledrectangle($image, $left + $space + $i * ($bar_width + $space), $top + ($this->img_height - $top - $down) - $bar_height[$i], $left + $space + $i * ($bar_width + $space) + $bar_width, ($this->img_height - $down) - 1, $bar_color);
			imagestring($image, 1, $left + $space + $i * ($bar_width + $space), $top + ($this->img_height - $top - $down) + 2, $this->items[$i], $img_color);
		}

		//画出将数据
		for($i = 0; $i < count($data); $i++){
			imagestring($image, 1, $left + $space + $i * ($bar_width + $space) + 2, $top + ($this->img_height - $top - $down) - $bar_height[$i] - 10, $data[$i],  $img_color);
		}
		header('Content-type: image/jpeg');
		imagejpeg($image, '', 100);
		imagedestroy($image);
	}

	/**
	 * 取得在椭圆心为（0，0）的椭圆上 x,y点的值
	 */
	public function pie_point($deg,$va,$vb){
		$x = cos($this->deg2Arc($deg)) * $va;
		$y = sin($this->deg2Arc($deg)) * $vb;
		return array($x, $y);
	}

	/**
	 * 计算圆弧角度
	 */
	public function deg2Arc($degrees) {
		return $degrees * (pi()/180.0);
	}

	/**
	 * 转换rgb值
	 */
	public function getRGB($color){
		$R = ($color>>16) & 0xff;
		$G = ($color>>8) & 0xff;
		$B = ($color) & 0xff;
		return array($R, $G, $B);
	}

}

/* 饼图 
 * $newStat->set_size("150", "80");
 * $newStat->stat_pie();
 */

/* 曲线
 * $bkcolor = array(255, 255, 255);
 * $newStat->set_items($str);
 * $newStat->set_size("320", "110");
 * $newStat->set_bkcolor($bkcolor);
 * $newStat->stat_line(); 
*/

/* 柱状图
 * $newStat->set_size("1200","150");
 * $newStat->set_border(20,20,20,20);
 * $newStat->set_items($str);
 * $bkcolor = array(233, 246, 253);
 * $newStat->set_bkcolor($bkcolor);
 * $newStat->stat_bar();
 */

/*
$data = "9, 5, 4, 6, 10";
$str = iconv("utf-8", "utf-8", "1月, 2月, 3月, 4月, 5月");
$newStat = new WapImageStat();	
$newStat->set_data($data);
$bkcolor = array(255, 255, 255);
$newStat->set_items($str);
$newStat->set_size("320", "110");
$newStat->set_bkcolor($bkcolor);
$newStat->stat_line();
*/
?>