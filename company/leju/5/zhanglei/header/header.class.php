<?php
/**
 * @author coderookie <zhanglei19881228@sina.com>
 * @todo describe the http
 */
class ResponseHeader{
    private static $class = null;
    // 响应头状态码
    private static $statusCode = array(
        '100' => 'Continue',
        '101' => 'Switching Protocols',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '306' => '(Unused)',
        '307' => 'Temporary Redirect',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '407' => 'Proxy Authentication Required',
        '408' => 'Request Timeout',
        '409' => 'Conflict',
        '410' => 'Gone',
        '411' => 'Length Required',
        '412' => 'Precondition Failed',
        '413' => 'Request Entity Too Large',
        '414' => 'Request-URI Too Long',
        '415' => 'Unsupported Media Type',
        '416' => 'Requested Range Not Satisfiable',
        '417' => 'Expectation Failed',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '502' => 'Bad Gateway',
        '503' => 'Service Unavailable',
        '504' => 'Gateway Timeout',
        '505' => 'HTTP Version Not Supported',
    );
    
    private static $types = array( 
        'ez'    => 'application/andrew-inset', 
        'hqx'   => 'application/mac-binhex40', 
        'cpt'   => 'application/mac-compactpro', 
        'doc'   => 'application/msword', 
        'bin'   => 'application/octet-stream', 
        'dms'   => 'application/octet-stream', 
        'lha'   => 'application/octet-stream', 
        'lzh'   => 'application/octet-stream', 
        'exe'   => 'application/octet-stream', 
        'class' => 'application/octet-stream', 
        'so'    => 'application/octet-stream', 
        'dll'   => 'application/octet-stream', 
        'oda'   => 'application/oda', 
        'pdf'   => 'application/pdf', 
        'ai'    => 'application/postscript', 
        'eps'   => 'application/postscript', 
        'ps'    => 'application/postscript', 
        'smi'   => 'application/smil', 
        'smil'  => 'application/smil', 
        'mif'   => 'application/vnd.mif', 
        'xls'   => 'application/vnd.ms-excel', 
        'ppt'   => 'application/vnd.ms-powerpoint', 
        'wbxml' => 'application/vnd.wap.wbxml', 
        'wmlc'  => 'application/vnd.wap.wmlc', 
        'wmlsc' => 'application/vnd.wap.wmlscriptc', 
        'bcpio' => 'application/x-bcpio', 
        'vcd'   => 'application/x-cdlink', 
        'pgn'   => 'application/x-chess-pgn', 
        'cpio'  => 'application/x-cpio', 
        'csh'   => 'application/x-csh', 
        'dcr'   => 'application/x-director', 
        'dir'   => 'application/x-director', 
        'dxr'   => 'application/x-director', 
        'dvi'   => 'application/x-dvi', 
        'spl'   => 'application/x-futuresplash', 
        'gtar'  => 'application/x-gtar', 
        'hdf'   => 'application/x-hdf', 
        'js'    => 'application/x-javascript', 
        'skp'   => 'application/x-koan', 
        'skd'   => 'application/x-koan', 
        'skt'   => 'application/x-koan', 
        'skm'   => 'application/x-koan', 
        'latex' => 'application/x-latex', 
        'nc'    => 'application/x-netcdf', 
        'cdf'   => 'application/x-netcdf', 
        'sh'    => 'application/x-sh', 
        'shar'  => 'application/x-shar', 
        'swf'   => 'application/x-shockwave-flash', 
        'sit'   => 'application/x-stuffit',  
        'tar'   => 'application/x-tar', 
        'tcl'   => 'application/x-tcl', 
        'tex'   => 'application/x-tex', 
        'texi'  => 'application/x-texinfo', 
        't'     => 'application/x-troff', 
        'tr'    => 'application/x-troff', 
        'roff'  => 'application/x-troff', 
        'man'   => 'application/x-troff-man', 
        'me'    => 'application/x-troff-me', 
        'ms'    => 'application/x-troff-ms', 
        'ustar' => 'application/x-ustar', 
        'src'   => 'application/x-wais-source', 
        'xhtml' => 'application/xhtml+xml', 
        'xht'   => 'application/xhtml+xml', 
        'zip'   => 'application/zip', 
        'au'    => 'audio/basic', 
        'snd'   => 'audio/basic', 
        'mid'   => 'audio/midi', 
        'midi'  => 'audio/midi', 
        'kar'   => 'audio/midi', 
        'mpga'  => 'audio/mpeg', 
        'mp2'   => 'audio/mpeg', 
        'mp3'   => 'audio/mpeg', 
        'aif'   => 'audio/x-aiff', 
        'aiff'  => 'audio/x-aiff', 
        'aifc'  => 'audio/x-aiff', 
        'm3u'   => 'audio/x-mpegurl', 
        'ram'   => 'audio/x-pn-realaudio', 
        'rm'    => 'audio/x-pn-realaudio', 
        'rpm'   => 'audio/x-pn-realaudio-plugin', 
        'ra'    => 'audio/x-realaudio', 
        'wav'   => 'audio/x-wav', 
        'pdb'   => 'chemical/x-pdb', 
        'xyz'   => 'chemical/x-xyz', 
        'bmp'   => 'image/bmp', 
        'gif'   => 'image/gif', 
        'ief'   => 'image/ief', 
        'jpeg'  => 'image/jpeg', 
        'jpg'   => 'image/jpeg', 
        'jpe'   => 'image/jpeg', 
        'png'   => 'image/png', 
        'css'   => 'text/css', 
        'html'  => 'text/html', 
        'htm'   => 'text/html', 
        'asc'   => 'text/plain', 
        'txt'   => 'text/plain', 
        'rtx'   => 'text/richtext', 
        'rtf'   => 'text/rtf', 
        'sgml'  => 'text/sgml', 
        'sgm'   => 'text/sgml', 
        'tsv'   => 'text/tab-separated-values', 
        'wml'   => 'text/vnd.wap.wml', 
        'wmls'  => 'text/vnd.wap.wmlscript', 
        'etx'   => 'text/x-setext', 
        'xsl'   => 'text/xml', 
        'xml'   => 'text/xml', 
        'mpeg'  => 'video/mpeg', 
        'mpg'   => 'video/mpeg', 
        'mpe'   => 'video/mpeg', 
        'qt'    => 'video/quicktime', 
        'mov'   => 'video/quicktime', 
        'mxu'   => 'video/vnd.mpegurl', 
        'avi'   => 'video/x-msvideo', 
        'movie' => 'video/x-sgi-movie', 
        'ice'   => 'x-conference/x-cooltalk', 
    ); 
    
    private function __construct(){
        
    }
    
    // 设置响应状态信息
    public function setHeaderStatus($code, $text = ''){
        if(isset(self::$statusCode[$code])){
            $protocol = $_SERVER['SERVER_PROTOCOL'];
            $description = !empty($text) ? $text : self::$statusCode[$code];
            $status = $protocol . " " . $code . " " . $description;
            header($status);
        }else{
            throw new Exception("http status code is not exists");
        }
    }
    
    // 设置响应信息的编码
    public function setHeaderCharset($character = 'utf-8', $type = 'html'){
        $charset = sprintf("Content-type: %s; charset=%s", isset(self::$types[$type]) ? self::$types[$type] : self::$types['html'], $character);
        header($charset);
    }
    
    // 设置重定向
    public function setHeaderLocation($url){
        $location = sprintf("Location: %s", $url);
        header($location);
    }
    
    // secode后刷新页面, 并有提示信息
    public function setHeaderRefresh($second, $url, $description){
        $refresh = sprintf("Refresh: %u; url=%s", $second, $url);
        header($refresh);
        echo $description;
    }
    
    // 利用header下载附件
    public function downloadAttachement($path, $filename){
        if(!file_exists($path)){
            throw new Exception("the attcachement is not exists");
        }
        $file_arr = pathinfo($path);
        $extension = $file_arr['extension'];
        if(!isset(self::$types[$extension])){
            throw new Exception("the attachement extension is not defined");
        }
        $header_content_type = self::$types[$extension];
        header("Content-type: $header_content_type");
        header("Content-Disposition: attachment; filename=" . $filename);
        readfile($path);
    }
    
    // 允许浏览器缓存
    public function setPragma(){
        header("Pragma: private");
    }

    // 通过expire来设置浏览器缓存
    public function setCacheByExpires($time = 0){
        $expire = gmdate("r", time() + $time + 8*3600);
        header("Expires: $expire");
    }
    
    /*
     * 浏览器第一次请求服务器, 没有if_modified_since, 返回200
	 * 并且响应last_modified的时间, 此时last_modified为当前时间 + 需要缓存的时间
     * 浏览器第二次或者后面多次请求服务器, 带有if_modified_since, 判断当前的时间与if_modified_since做比较
     * 如果当前时间 < if_modified_since 还在缓存中
	 *     返回304
	 * 如果当前时间 > if_modified_since 表示已经过期
	 *     返回200, 并且将last_modified设置为当前的时间 + 需要缓存的时间
     */
    public function setCacheByLastModify($time = 0){
		$current_time = time() + 8*3600;

		// 需要缓存到什么时间
		$cache_time = $current_time + $time;
		$cache_date = gmdate('r', $cache_time);

        $http_if_modified_since = !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;

        if(!empty($http_if_modified_since)){
			$http_if_modified_since_time = strtotime($http_if_modified_since);
			if($current_time < $http_if_modified_since_time){
				// 当前时间小于if_modified_since, 需要缓存
				$this->setHeaderStatus(304);
			}else{
				// 当前时间大于if_modified_since, 缓存已经过期, 设置新的缓存时期
				$this->setHeaderStatus(200);
				header("Last-Modified: $cache_date");
			}
        }else{
			// 第一次请求服务器
			$this->setHeaderStatus(200);
			header("Last-Modified: $cache_date");
        }
    }
    
    // 通过cache-controller来设置浏览器缓存
    public function setCacheByController($maxAge = 0){
        $cacheController = sprintf("Cache-Control: max-age=%u", $maxAge);
        header($cacheController);
    }
    
    // 通过Etags来设置浏览器缓存
    public function setCacheByEtags($etags = ''){
        $http_if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : ' ';
        if(isset($http_if_none_match) && $etags == $http_if_none_match){
            $this->setHeaderStatus("304");
        }
        header("Etag: $etags");
    }
    
    // 禁止浏览器缓存
    public function disableBrowserCache(){
        header("Expires: -1");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Pragma: no-cache");
        header("Cache-Controller: no-cache, no-store, must-revalidate, max-age=0");
        header("Pragma: no-cache");
    }
    
    public static function getInstance(){
        if(self::$class === null){
            self::$class = new ResponseHeader();
        }
        return self::$class;
    }
    
}
?>