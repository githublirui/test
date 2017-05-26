<?PHP
// multi curl
class EpiCurl {
    const TIMEOUT = 3;
    static $inst = null;
    static $singleton = 0;
    private $mc;
    private $msgs;
    private $running;
    private $requests = array();
    private $responses = array();
    private $properties = array();
    private $result = array();

    function __construct($urls) {
        $this->mc = curl_multi_init();
        $this->properties = array(
            'code' => CURLINFO_HTTP_CODE, 
            //'time' => CURLINFO_TOTAL_TIME, 
            //'length' => CURLINFO_CONTENT_LENGTH_DOWNLOAD, 
            //'type' => CURLINFO_CONTENT_TYPE
        );

        $keys = array();
        foreach ($urls as $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);
            $keys[(string) $ch] = $url;
            $this->addCurl($ch);
        }

        foreach ($keys as $key => $url) {
            $result = $this->_getResult($key);
            $result['url'] = $url;
            $this->result[] = $result;
        }

        curl_multi_close($this->mc);
    }

    public function getResult() {
        return $this->result;
    }

    private function addCurl($ch) {
        $key = (string) $ch;
        $this->requests[$key] = $ch;
        
        $res = curl_multi_add_handle($this->mc, $ch);
        if ($res == 0) {
            curl_multi_exec($this->mc, $active);
        }
    }

    private function _getResult($key = null) {
        if ($key != null) {
            if (isset($this->responses[$key])) {
                return $this->responses[$key];
            }
            
            $running = null;
            do {
                $resp = curl_multi_exec($this->mc, $runningCurrent);
                if ($running !== null && $runningCurrent != $running) {
                    $this->storeResponses();
                    if (isset($this->responses[$key])) {
                        return $this->responses[$key];
                    }
                }
                $running = $runningCurrent;
            } while ($runningCurrent > 0);
        }
        
        return false;
    }

    private function storeResponses() {
        while ($done = curl_multi_info_read($this->mc)) {
            $key = (string) $done['handle'];
            $this->responses[$key]['data'] = curl_multi_getcontent($done['handle']);
            foreach ($this->properties as $name => $const) {
                $this->responses[$key][$name] = curl_getinfo($done['handle'], $const);
            }
            curl_multi_remove_handle($this->mc, $done['handle']);
        }
    }

}