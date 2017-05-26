<?php
/**
 * 计时函数
 * @author chenyihong <jinglingyueyue@gmail.com>
 */

class Timer {
    private $_start;
    private $_end;

    public function start() {
        $this->_start = self::_microToSecond();
    }

    public function stop() {
        $this->_end = self::_microToSecond();
    }

    /**
     * 获取开始到结束的时间
     * @param $unit string 返回时间的单位
     *          - 'ms', 's'
     * @return float
     */
    public function spent($unit = 'ms') {
        $time = $this->_end - $this->_start;
        switch (strtolower($unit)) {
            case 'ms':
                return $time * 1000;
                break;

            case 's':
                return $time;
                break;
        }
    }

    private function _microToSecond($microtime = '') {
        if (empty($microtime)) {
            $microtime = microtime();
        }
        $arr = explode(' ', $microtime);
        return number_format($arr[1] + $arr[0], 8, '.', '');
    }
}
