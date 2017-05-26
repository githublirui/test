<?php
namespace Bird;
include_once('oop1.class.php');
use Food\Food;

class Bird{

	public function __construct(){
		if(class_exists('Food\Food')){
			print_r(get_class_vars('Food\Food'));
		}	
	}

	public function eat($food){
		echo $food->name;
	}

	// 控制错误
	public function __call($action, $args){
		throw new \Exception ("Bird类中没有" . $action . "这个函数被提供使用");
	}

}

$food = new Food('小虫');
$bird = new Bird();
//$bird->eat($food);
//$bird->aaa();
?>
