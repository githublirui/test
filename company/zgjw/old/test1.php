<?php

class Person {

    public $name;
    protected $age;
    private $salary;

    function __construct($name, $age, $salary) {
        $this->name = $name;
        $this->age = $age;
        $this->salary = $salary;
    }

    public function showinfo() {
//这表示三个修饰符都可以在本类内部使用 
        echo $this->name . "||" . $this->age . "||" . $this->salary;
    }

}

$p1 = new Person('张三', 20, 3000);
//这里属于类外部，那么如果用下面的方法访问age和salary都会报错 
// echo $p1->age; echo$p1->salary; 
?> 