<?php
/**
 * @author zhanglei <zhanglei19881228@sina.com>
 * @date 2015-03-23 10:25
 * @brief 观察者模式, 被观察者中注册观察者, 当被观察者出现变化, 告诉观察者
 */

// 被观察者抽象类
abstract class Observed{
    
    // 注册观察者
    abstract public function register($object);
    
    // 通知观察者
    abstract public function notice();
    
}

// 观察者抽象类
abstract class Observer{
    
    abstract public function logger($object);
    
}

// 被观察者
class Linux extends Observed{
    
    private $_observers = array();
    private $_message   = null;
    
    //  注册观察者
    public function register($observer){
        $this->_observers[] = $observer;
    }
    
    // 通知观察者
    public function notice(){
        if(!empty($this->_observers)){
            foreach($this->_observers as $observer){
                $observer->logger($this);
            }
        }
    }
    
    // 被观察者发生变化
    public function shutdown(){
        $this->_message = 'linux will be shutdown';
    }
    
    public function getMessage(){
        return $this->_message;
    }
}

// 观察者
class Nginx extends Observer{
    
    public function logger($observed){
        echo sprintf("Nginx will not be used, cause %s <br />", $observed->getMessage());
    }
    
}

// 观察者
class Apache extends Observer{
    
    public function logger($observed){
        echo sprintf("Apache will not be used, cause %s <br />", $observed->getMessage());
    }
    
}

$linux = new Linux();

$linux->register(new Nginx);
$linux->register(new Apache);

$linux->shutdown();
$linux->notice();