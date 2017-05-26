<?php
/**
 * 验证码生成
 *
 * @author nkapproach@gmail.com 2009-6-10
 *
 * @modify chenchaofei 2011-8-8
 *
 */
require_once (CODE_BASE2 . '/util/session/SessionNamespace.class.php');

class CheckCode {
    /**
     * @brief 检查是否正确
     *
     * @param string $str 用户输入的验证码
     * @param string $check_code_tag 多验证码时的区分标识 --默认：Null
     * @param boolean $isDel 验证成功时，是否要删除Session --默认：false
     *
     * @return booelan
     */
    public static function isValid($str, $check_code_tag = null, $isDel = false) {
        $name = self::_getKey ( $check_code_tag );
        $checkcode = SessionNamespace::getValue ( $name, '' );
        $flag = strlen ( $str ) > 0 && strcasecmp ( $checkcode, $str ) == 0; // 不区分大小写
        if ($flag && $isDel) {
            self::delete ( $check_code_tag );
        }

        // 未通过检查的时候检查是否是万能钥匙key
        if (!$flag) {
//            self::_log(sprintf('fail[code=%s],[sessioncode=%s]', $str, $checkcode));
            $flag = self::_checkIsAuthPasskey($str);
        }
        return $flag;
    }

    /**
     * @brief 检查是否是验证码的万能钥匙, 当前只有内网192.168xxx使用
     * @return boolean true/合法, false/非法
     */
    private static function _checkIsAuthPasskey($str) {
        include_once CODE_BASE2 . '/util/http/HttpNamespace.class.php';
        $clienIp    = HttpNamespace::getIp(false);
        if (substr($clienIp, 0, 7)  == '127.0.0' || substr($clienIp, 0, 7)  == '192.168') {
            if (file_exists(GANJI_CONF . '/.local.config.php')) {
                include_once GANJI_CONF . '/.local.config.php';
                if (isset($GLOBALS['ganji_checkcode_authpasskey']) && $GLOBALS['ganji_checkcode_authpasskey'] == $str) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @brief 删除验证码
     *
     * @param string $check_code_tag 多验证码时的区分标识 --默认：Null
     *
     * @return void
     */
    public static function delete($check_code_tag = null) {
        $name = self::_getKey ( $check_code_tag );
        SessionNamespace::delete ( $name );
    }

    /**
     * @brief 保存验证码到session
     *
     * @param string $check_code_tag 多验证码时的区分标识 --默认：Null
     *
     * @return void
     */
    public static function save($val, $check_code_tag = null) {
        if (! empty ( $val )) {
            $name = self::_getKey ( $check_code_tag );
            SessionNamespace::setValue ( $name, $val );
        }
    }

    /**
     * @brief 显示一张简单的图片，同时将值保存到session
     * @param string $check_code_tag 多验证码时的区分标识
     *
     * @return string
     */
    public static function simple($check_code_tag = null) {
        $randval = mt_rand ( 1000, 9999 );
        header ( "Content-type: image/gif" );
        $im = imagecreate ( 50, 17 );
        $black = imagecolorallocate ( $im, 150, 100, 100 );
        $white = imagecolorallocate ( $im, 255, 255, 255 );
        imagestring ( $im, 5, 8, 0, $randval, $white );
        imagegif ( $im );
        ImageDestroy ( $im );
        self::save ( $randval, $check_code_tag );
        return $randval;
    }

    /**
     * @brief 合成一张图片，同时将值保存到session
     *
     * @param array $param 配置
     * @param string $check_code_tag 多验证码时的区分标识
     * @param string $text 验证码内容, 如果为空则自动随即生成
     *
     * @return string
     *
     */
    public static function complex($param = array(), $check_code_tag = null, $text = '') {
        $default = array ('width' => 120, //图片宽
            'height' => 40, //图片高
            'len' => 4, //生成几位验证码
            'word_zoom' => 0.8, //文字缩放比例
            'bgcolor' => '#ffffff', //背景色
            'noise' => true, //生成杂点
            'noisenum' => 200, //杂点数量
            'border' => false, //边框
            'bordercolor' => '#000000' );
        $param = array_merge ( $default, $param );
        include_once dirname ( __FILE__ ) . '/captcha/Captcha.class.php';
        $captcha = new Captcha ();
        $randval = $captcha->CreateImage ($param, $text);
        self::save ( $randval, $check_code_tag );
        return $randval;
    }

    /**
     * @brief 默认新验证码
     * @param $param
     * @param $check_code_tag
     * @param $text
     * @return string
     * @see complex_new
     */
    public static function complex_new_default($param = array(), $check_code_tag = null, $text = '') {
        $colors = array(
//                array(27,78,181), // blue
//                array(22,163,35), // green
//                array(214,36,7),  // red
//                '#707070',  // 灰色
                '#f90',  // 橘黄色
                '#693',  // 橘红色
                '#f63',  // 绿色
        );
        $backgrounds = array(
            'bg3.jpg',
            'bg4.jpg',
            'bg6.jpg',
        );
        $background_key = array_rand($backgrounds);
        // 设置 干扰线 和字体一个颜色
        $lineColor = $textColor = $colors[mt_rand(0, count($colors)-1)];
        $default = array(
            'width'      => 120,
            'height'     => 36,
            'num_lines'  => 2,
            'line_color' => $lineColor,
            'text_color' => $textColor,
            'background' => CODE_BASE2 . '/util/check_code/captcha_new/backgrounds/'.$backgrounds[$background_key],
        );
        $options = array_merge ( $default, $param );
        return CheckCode::complex_new($options, $check_code_tag, $text);
    }

   /**
     * @brief 合成一张图片，同时将值保存到session
     *
     * @param array $param 配置
     * @param string $check_code_tag 多验证码时的区分标识
     * @param string $text 验证码内容, 如果为空则自动随即生成
     *
     * @return string
     *
     */
    public static function complex_new($param = array(), $check_code_tag = null, $text = '') {
        require_once dirname ( __FILE__ ) . '/captcha_new/securimage.php';
        $default = array(
            'captchaId'     => sha1(uniqid($_SERVER['REMOTE_ADDR'] . $_SERVER['REMOTE_PORT'])),
            'no_session'    => true,
            'no_exit'       => true,
            'use_sqlite_db' => false,
            'num_lines' => 3,
            'code_length' => 4,
            'send_headers'  => false);
        $options = array_merge ( $default, $param );
        if (!empty($text)){
            $options['display_value'] = $text;
        }
        if (isset($param['width']) && $param['width'] > 0){
            $options['image_width'] = $param['width'];
        }
        if (isset($param['height']) && $param['height'] > 0){
            $options['image_height'] = $param['height'];
        }
        if (isset($param['noise_level']) && $param['noise_level'] > 0){
             $options['noise_level'] = $param['noise_level'];
        }
        $strBackground = '';
        if (isset($param['background']) && !empty($param['background'])){
            $strBackground = $param['background'];
        }
        $img = new Securimage($options);
        ob_start();   // start the output buffer
        $randval = $img->show($strBackground); // output the image so it is captured by the buffer
        $imgBinary = ob_get_contents(); // get contents of the buffer
        ob_end_clean(); // turn off buffering and clear the buffer
        header('Content-Type: image/png');
        header('Content-Length: ' . strlen($imgBinary));
        echo $imgBinary;
        self::save ( $randval, $check_code_tag );
        return $randval;
    } 
    /**
     *
     * @brief 取得验证码的合法名称 --默认：Null
     * @param String $tag
     *
     * @return string
     */
    private static function _getKey($tag = null) {
        //$name = 'da638664748';
        $name = 'checkcode';
        if (!empty ( $tag )) {
            $name .= '_' . $tag;
        }
        return $name;
    }
    
    private static function _log($msg = '', $category = 'checkcode') {
        if (class_exists('Logger') && method_exists('Logger', 'logWarn')) {
            Logger::logWarn($msg, $category);
        }
    }

}

