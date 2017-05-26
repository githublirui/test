<?PHP

/**
 * 定时脚本父类
 * 
 * @author longweiguo@ganji.com
 * @version 2011-09-26
 */

class CronNamespace {
    //文件锁文件夹路径
    public static $lockDirPath = '/tmp/cron/file_lock';
    
    //日志文件夹路径
    public static $logDirPath = '/tmp/cron/log';
    
    //日志文件夹路径
    public static $logFilePathTmp = null;
    
    public static $phpTool = '/usr/local/webserver/php/bin/php';
    
    //允许的最大内存消耗
    public static $maxMemory = 5324800; //50M

    public static $killEnable = false;
    
    private $fp = null;
    
    private $title = '';
    
    private $filePath;
    
    private $lockFilePath;
    
    private $logFilePath;
    
    private $date;
    
    private $id;
    
    private $cmd;

    public function run($title, $cmd, $resultLogFile = '', $estimatedTime = 0, $debug = false, $config = array(), $canConcurrentRun = false) {
        self::setConfig($config);
        return new CronNamespace($title, $cmd, $resultLogFile, $estimatedTime, $debug, $canConcurrentRun);
    }

    private function __construct($title, $cmd, $resultLogFile = '', $estimatedTime = 0, $debug = false, $canConcurrentRun) {
        $this->_init($title, $cmd);
        
        if (! empty($resultLogFile)) {
            $resultLogFileDir = dirname($resultLogFile);
            if (! $this->createDir($resultLogFileDir)) {
                die("Create dir failed [" . $resultLogFileDir . "]");
            }
        } else {
            $resultLogFile = '/dev/null';
        }
        
        if ($debug) {
            $this->exec($cmd);
            return;
        }
        
        $this->writeLog('', 'start');
        $startTime = time();
        
        if (!$this->checkFileLock()) {
            if ($canConcurrentRun) {
                $this->writeLog("Last process is running, concurrent run start", 'error');
            } else {
                $this->writeLog("Last process is running", 'error');
                $this->writeLog('', 'end');
                return false;
            }
        }
        
        $cmd = "nohup {$cmd} >> {$resultLogFile} & echo $!";
        $this->writeLog($cmd, 'debug');
        $pid = shell_exec($cmd);
        
        $timeout = false;
        $startMemory = $this->getMemory($pid);
        $maxMemory = 0;
        $memoryOut = false;
        while ($this->isRunning($pid)) {
            sleep(1);
            
            // 超时只报警，不停止
            if ($estimatedTime > 0 && time() - $startTime > $estimatedTime) {
                if (! $timeout) {
                    $this->writeLog('Time more than ' . $estimatedTime . ' seconds', 'error');
                }
                $timeout = true;
            }
            
            $newMemory = $this->getMemory($pid);
            $diffMemory = $newMemory - $startMemory;
            if ($diffMemory > $maxMemory) {
                $maxMemory = $diffMemory;
            }
            if ($maxMemory > self::$maxMemory) {
                if (! $memoryOut) {
                    $this->writeLog('Memory more than ' . $this->formatSize(self::$maxMemory), 'error');
                }
                $memoryOut = true;
                
                if (self::$killEnable) {
                    exec("kill -KILL $pid");
                    $this->writeLog('Killed', 'error');
                }
            }
        }
        
        $this->writeLog('total: ' . (time() - $startTime) . ' seconds', 'end');
        
        $this->closeFileLock();
    }

    private function _init($title, $cmd) {
        $this->title  = $title;
        $this->cmd    = $cmd;
        $this->getLockFilePath();
        $this->getLogFilePath();
        $this->getId();
    }

    private function exec($cmd) {
        $descriptorspec = array(
            0 => array(
                'pipe', 
                'r'
            ), 
            1 => array(
                'pipe', 
                'w'
            ), 
            2 => array(
                'pipe', 
                'r'
            )
        );
        
        $resource = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($resource)) {
            fclose($pipes[0]);
            
            while (! feof($pipes[1])) {
                echo fgets($pipes[1]);
            }
            fclose($pipes[1]);
            
            echo stream_get_contents($pipes[2]);
            fclose($pipes[2]);
            
            $exit_code = proc_close($resource);
        }
        exit();
    }

    private function getMemory($pid) {
        $output = array();
        exec("ps -o rss -p $pid", $output);
        return $output[1] * 1024;
    }

    public static function setConfig($config) {
        if (! empty($config) && is_array($config)) {
            if (! empty($config['lockDirPath'])) {
                self::$lockDirPath = $config['lockDirPath'];
            }
            if (! empty($config['logDirPath'])) {
                self::$logDirPath = $config['logDirPath'];
            }
            if (! empty($config['maxMemory']) && $config['maxMemory'] > 0) {
                self::$maxMemory = $config['maxMemory'];
            }
            if (array_key_exists('killEnable', $config)) {
                self::$killEnable = $config['killEnable'];
            }
            if (array_key_exists('logFilePath', $config)) {
                self::$logFilePathTmp = $config['logFilePath'];
            }
        }
    }

    protected function getLockFilePath() {
        if (! empty($this->lockFilePath)) {
            return $this->lockFilePath;
        }
        $md5Path = md5($this->cmd);
        $lockDirPath = self::$lockDirPath . '/' . substr($md5Path, 0, 2);
        if (! $this->createDir($lockDirPath)) {
            die("Create dir failed [" . $lockDirPath . "]");
        }
        
        $this->lockFilePath = $lockDirPath . '/' . $md5Path . '.log';
        return $this->lockFilePath;
    }

    protected function getLogFilePath() {
        if (! empty($this->logFilePath)) {
            return $this->logFilePath;
        }
        
        if (! $this->createDir(self::$logDirPath)) {
            die("Create dir failed [" . self::$logDirPath . "]");
        }
        
        if (self::$logFilePathTmp) {
            $this->logFilePath = self::$logFilePathTmp;
            return $this->logFilePath;
        }
        
        $this->logFilePath = self::$logDirPath . '/' . $this->getDate() . '.log';
        return $this->logFilePath;
    }

    protected function getId() {
        if (! empty($this->id)) {
            return $this->id;
        }
        $this->id = substr($this->getLockFilePath(), - 3) . substr(time() + '', - 5);
        return $this->id;
    }

    protected function getDate() {
        if (! empty($this->date)) {
            return $this->date;
        }
        $this->date = date('Y-m-d');
        return $this->date;
    }

    //$type，值有：start, error, end
    protected function writeLog($msg = '', $type = '') {
        echo "[{$type}]\t{$msg}\n";
        
        $time = date("Y-m-d H:i:s");
        $title = $this->title;
        $id = $this->getId();
        error_log("{$time}\t{$title}[{$id}]\t[{$type}]\t{$msg}\n", 3, $this->getLogFilePath());
    }

    private function checkFileLock() {
        $this->fp = fopen($this->getLockFilePath(), 'w+');
        if (! flock($this->fp, LOCK_EX | LOCK_NB)) {
            fclose($this->fp);
            return false;
        }
        return true;
    }

    private function closeFileLock() {
        if ($this->fp) {
            flock($this->fp, LOCK_UN);
            fclose($this->fp);
        }
    }

    private function isRunning($pid) {
        exec("ps $pid", $ProcessState);
        return (count($ProcessState) >= 2);
    }

    private function createDir($dir) {
        if (empty($dir)) {
            return false;
        }
        if (! is_dir($dir)) {
            return mkdir($dir, 0755, true);
        }
        return true;
    }

    private function formatSize($num) {
        //$mem_usage = memory_get_usage(true); 
        

        if ($num < 1024)
            return $num . "b";
        elseif ($num < 1048576)
            return round($num / 1024, 2) . "k";
        else
            return round($num / 1048576, 2) . "m";
    }
}
