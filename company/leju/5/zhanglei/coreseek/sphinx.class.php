<?php
class Sphinx{
    
    // 单例
    private static $class = null;
    
    // sphinx的api文件路径
    private $api = "../../../coreseek/api/sphinxapi.php";
    
    // sphinx client 的对象
    public $sphinxClient;
    
    // coreseek所在的磁盘
    private $disk = "D:";
    
    // sphinx的路径
    private $path = "webdev\coreseek";
    
    private $wholePath;
    
    // 在类内实例化self
    private function __construct($conf){
        $this->wholePath = $this->disk . '\\' . $this->path;
        if(!file_exists($this->wholePath)){
            throw new Exception('sphinx path is error');
        }
        if(file_exists($this->api)){
            include_once($this->api);
        }else{
            throw new Exception("sphinx api is not exists");
        }
        $this->checkConf($conf);
        $this->sphinxClient = new SphinxClient();
        $this->sphinxClient->SetServer($conf['host'], $conf['port']);
        $this->sphinxClient->SetConnectTimeout(3);
        $this->sphinxClient->SetArrayResult(true);
        $this->sphinxClient->SetMaxQueryTime(3);
    }
    
    // 将keyword做过滤处理
    public function EscapeString($keyword){
        if(!$keyword){
            throw new Exception("keyword is null");
        }
        return $this->sphinxClient->EscapeString($keyword);
    } 
    
    // 从结果集中得到word的数组, 方便做高亮显示
    public function getWords($results){
        if(!$results['words'] || !is_array($results['words'])){
            throw new Exception("the sphinx result words is null or not a array");
        }
        return implode(" ", array_keys($results['words']));
    }
    
    // 得到match后的结果, 支持分页
    public function Query($keyword, $indexer, $start = false, $limit = false){
        $keyword = $this->EscapeString($keyword);
        if($start !== false && $limit !== false){
            $this->sphinxClient->SetLimits($start, $limit, 100);
        }
        return $this->sphinxClient->Query($keyword, $indexer);
    }
    
    // 检查配置文件
    private function checkConf($conf){
        if(!isset($conf['host'])){
            throw new Exception("sphinx host is null");
        }elseif(!isset($conf['port'])){
            throw new Exception("sphinx port is null");
        }
    }
    
    /*
     * 通过配置文件索引传过来的参数
     * rotate(轮换) 继续searchd的服务, 将新的文档重新索引成.new的格式, 完成后会通知searchd, 将原来的索引加上.old, 将.new替换成原索引 
     */
    public static function indexer($index = ''){
        if($index == '') $index = "all";
        system("$this->disk");
        system("cd $this->path");
        system("bin\indexer --config etc\csft_mysql.conf --rotate --$index");
    }
    
    /** 
     * 通过配置文件合并主索引以及增量索引
     */
    public static function mergeIndexer($main, $delta){
        if(!$main || !$delta){
            throw new Exception('main index or delta index is null');
            system("$this->disk");
            system("cd $this->path");
            system("bin\indexer --config etc\csft_mysql.conf --merge $main $delta --merge-dst-range deleted 0 0");
        }
    }
    
    // 返回致命错误
    public function GetLastError(){
        return $this->sphinxClient->GetLastError();
    }
    
    // 返回警告错误
    public function GetLastWarning(){
        return $this->sphinxClient->GetLastWarning();
    }
    
    // 单例
    public static function getInstance($conf){
        if(self::$class === null){
            self::$class = new Sphinx($conf);
        }
        return self::$class;
    }
    
}
?>