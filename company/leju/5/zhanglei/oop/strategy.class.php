<?php
/**
 * @author zhanglei <zhanglei19881228@sina.com>
 * @date 2014-06-12 17:00
 * @brief 策略设计模式, 将变化的动作分离出策略类, 由主题选择何种策略
 */
interface ModelInterface{
    public function getLists();
}

class Category implements ModelInterface{
    
    public function getLists(){
        echo "category lists<br />";
    }
    
}

class News implements ModelInterface{
    
    public function getLists(){
        echo "news lists<br />";
    }
    
}

class Strategy{
    
    public function getLists($model){
        return $model->getLists();
    }
    
}

$strategy = new Strategy;
$strategy->getLists(new Category);
$strategy->getLists(new News);


/* sort strategy parrern */
interface SortInterface{
    
    public function sort(&$array);
    
}

class Asc implements SortInterface{
    
    public function sort(&$array){
        sort($array);
    }
    
}

class Desc implements SortInterface{
    
    public function sort(&$array){
        return rsort($array);
    }
    
}

class StrategySort{
    
    private $array = array();
    
    public function __construct($array){
        if(empty($array) || !is_array($array)){
            throw new Exception('the params should be array');
        }
        $this->array = $array;
    }
    
    public function sort($object){
        $object->sort($this->array);
        return $this->getArray();
    }
    
    public function getArray(){
        return $this->array;
    }
    
}

$array = array(8, 15, 69, 698, 65, 5, 32, 67, 12);
$strategy_sort = new StrategySort($array);
$asc = $strategy_sort->sort(new Asc);
print_r($asc);
echo "<br />";
$desc = $strategy_sort->sort(new Desc);
print_r($desc);
?>